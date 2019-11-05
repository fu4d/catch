<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Finder\Finder;
use App\Entity\Orders;
use App\Form\OrdersType;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class OrdersController extends FOSRestController
{
    const LINE_SEPARATOR = "\n";

    public function index()
    {
        //$orders = $this->loadLocalData();

        $orders = $this->downloadData();
        if (!$orders) {
            throw $this->createNotFoundException(
                'There is no order yet'
            );
        }
        return $this->handleView($this->view($orders));
    }

    public function postOrderAction(Request $request)
    {
        $order	= new Orders();
        $form 	= $this->createForm(OrdersType::class, $order);
        $data 	= json_decode($request->getContent(),true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_OK));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    public function getOrderAction($order_id)
    {
        $order = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->findBy(['order_id' => $order_id]);

        if (!$order) {
            throw $this->createNotFoundException(
                'There is no orders with the following id: ' . $id
            );
        }

        return $this->handleView($this->view($order));
    }

    public function getOrdersAction()
    {
        $orders = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->findAll();

        if (!$orders) {
            throw $this->createNotFoundException(
                'There is no order yet'
            );
        }

        return $this->handleView($this->view($orders));
    }

    public function deleteOrderAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Orders::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException(
                'There is no order with the following id: ' . $id
            );
        }

        $em->remove($order);
        $em->flush();

        return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_DELETED));
    }

    public function updateOrderAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Orders::class)->find($id);
        $data 	= json_decode($request->getContent(),true);


        if (!$order) {
            throw $this->createNotFoundException(
                'There is no order with the following id: ' . $id
            );
        }

        $form = $this->createForm(OrdersType::class, $order);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_OK));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    protected function loadLocalData(){
        $finder = new Finder();
        $filesDir = $this->getParameter('source_directory');
        $finder->files()->in($filesDir);
        $orderCollections =[];
        foreach ($finder as $file) {
            $fileContent = $file->getContents();
            // collect and store data into DB
            $orders = $this->processData($fileContent);
            $orderCollections = array_merge($orderCollections,$orders);
        }


        return $orderCollections;
    }

    protected function downloadData(){
        // register a 's3://' wrapper with the official AWS SDK
        $s3Client = new S3Client([
                        'profile'       => 'default',
                        'region'        => 'ap-southeast-1',
                        'version'       => 'latest',
                    ]);
        $s3Client->registerStreamWrapper();
        $finder = new Finder();
        $orderCollections = [];
        foreach ($finder->in('s3://'.$this->getParameter("aws_bucket")) as $file) {
            $fileContent = $file->getContents();
            $orders = $this->processData($fileContent);
            $orderCollections = array_merge($orderCollections,$orders);
        }
        return $orderCollections;
    }

    protected function processData($content){
        $dlineContent = $this->deline($content);
        $datas = json_decode($dlineContent,true);
        $orders = [];
        foreach ($datas as $data) {
            $orderId = $data['order_id'];
            $timezone = new \DateTimeZone('UTC');
            $orderDate = date_create_from_format('D, d M Y H:i:s O', $data['order_date'], $timezone);
            $subtotalItems = 0;
            $subtotal = 0;

            // collect data item
            $totalItems = 0;
            $uniqueItem = [];

            $totalUniqueItems = 0;
            foreach ($data['items'] as $item) {
                $uniqueItem[] = [
                    "id"            => $item["product"]["product_id"],
                    "qty"           => $item['quantity'],
                    "price"         => $item['unit_price'],
                    "total_price"   => $item['unit_price']*$item['quantity']
                ];
                $totalItems += $item['quantity'];
                $subtotal += $item['unit_price']*$item['quantity'];
                $totalUniqueItems++;
            }
            // collect discount
            $discount = 0;
            if(isset($data['discounts'])){
                foreach ($data['discounts'] as $disc) {
                    $discount += $disc['value'];
                }
            }

            $averageUnitPrice = (float) $subtotal / $totalItems;
            $subtotalItems = $subtotal - $discount;
            $grandTotal = $subtotalItems + $data['shipping_price'];
            $customerId = $data["customer"]["customer_id"];
            $customerFname = $data["customer"]["first_name"];
            $customerLname = $data["customer"]["last_name"];
            $customerEmail = $data["customer"]["email"];
            $customerPhone = $data["customer"]["phone"];
            $customerStreet = $data["customer"]["shipping_address"]['street'];
            $customerSuburban = $data["customer"]["shipping_address"]['suburb'];
            $customerState = $data["customer"]["shipping_address"]['state'];
            $customerPostcode = $data["customer"]["shipping_address"]['postcode'];
            $shippingFee = $data["shipping_price"];

            // Get Geolocation by zipcode
            $coordinate = $this->getGeoLocation($customerPostcode);
            $longitude  = $coordinate['longitude'];
            $latitude   = $coordinate['latitude'];

            $entityManager = $this->getDoctrine()->getManager();
            $order  = new Orders();
            $order->setOrderId($orderId);
            $order->setOrderDatetime($orderDate);
            $order->setCustomerId($customerId);
            $order->setCustomerFname($customerFname);
            $order->setCustomerLname($customerLname);
            $order->setCustomerEmail($customerEmail);
            $order->setCustomerPhone($customerPhone);
            $order->setCustomerStreet($customerStreet);
            $order->setCustomerPostcode($customerPostcode);
            $order->setCustomerSuburb($customerSuburban);
            $order->setCustomerState($customerState);
            $order->setLongitude($longitude);
            $order->setLatitude($latitude);
            $order->setTotalOrderValue($subtotalItems);
            $order->setAverageUnitPrice($averageUnitPrice);
            $order->setDistinctUnitCount($totalUniqueItems);
            $order->setTotalUnitsCount($totalItems);
            $order->setSubtotal($subtotal);
            $order->setDiscount($discount);
            $order->setShippingFee($shippingFee);
            $order->setGrandTotal($grandTotal);
            $entityManager->persist($order);
            $entityManager->flush();

            //$orders[] = [$orderId,date(DATE_ISO8601, strtotime($orderDate->format('Y-m-d H:i:s'))),$subtotalItems,$averageUnitPrice,$totalUniqueItems,$totalItems,$customerState,$grandTotal];

            $orders[] = [
                        'order_id'          => $orderId,
                        'order_datetime'    => date(DATE_ISO8601, strtotime($orderDate->format('Y-m-d H:i:s'))),
                        'total_order_value' => $subtotalItems,
                        'longitude'         => $longitude,
                        'latitude'          => $latitude,
                        'grand_total'       => $grandTotal
                    ];

        }
        return $orders;
    }

    /**
     * Get longitude and latitude based on postcode.
     *
     * @param  string $jsonLines JSON Lines to deline into JSON
     * @return string
     */
    public function getGeoLocation($postcode){
        // it always interupted after 80 request and very slow response. So, I change to local data to make it faster
        //$url = "http://v0.postcodeapi.com.au/suburbs/".$postcode.".json";

        // I'm using local source to simulate geolocation process.
        $url = "http://localhost:8000/coordinate/".$postcode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoLocation = curl_exec($ch);
        curl_close($ch);
        $location = json_decode($geoLocation, true);
        $coordinate['longitude'] = $location[0]['longitude'];
        $coordinate['latitude'] = $location[0]['latitude'];
        return  $coordinate;
    }

    protected function writeCSV($orders){

        $fp = fopen($this->getParameter('files_directory')."/".strtotime('now')."_out.csv", "w");
        foreach ($orders as $data) {
            fputcsv(
                $fp,
                $data,
                ','
            );
        }
        fclose($fp);
    }


    /**
     * Delines given JSON Lines into JSON.
     *
     * @param  string $jsonLines JSON Lines to deline into JSON
     * @return string
     */
    public function deline($jsonLines)
    {
        if (empty($jsonLines)) {
            return json_encode([]);
        }
        $lines = [];
        $jsonLines = explode(self::LINE_SEPARATOR, trim($jsonLines));
        foreach ($jsonLines as $line) {
            $decodedLine = json_decode($line);
            $lines[] = $decodedLine;
        }
        return json_encode($lines);
    }

}
