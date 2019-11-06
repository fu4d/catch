<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Orders;

class DownloadController extends FOSRestController
{
    const LINE_SEPARATOR = "\n";

    /**
     * @Rest\Get("/csv")
     */
    public function getCsvAction()
    {
        $orders = $this->loadData();
        $orderCollections[] = ['order_id','order_datetime','total_order_value','average_unit_price','distinct_unit_count','total_units_count','customer_state','grand_total'];
        foreach ($orders as $order) {

            $datetime = (array)$order->getOrderDatetime();
            $orderCollections[] = [$order->getOrderId(),date(DATE_ISO8601, strtotime($datetime['date'])),$order->getTotalOrderValue(),$order->getAverageUnitPrice(),$order->getDistinctUnitCount(),$order->getTotalUnitsCount(),$order->getCustomerState(),$order->getGrandTotal()];
        }


        $fileName = "out.csv";
        $csvFile = $this->writeCSV($orderCollections, $fileName);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($csvFile);

        return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_OK));
    }

    protected function writeCSV($orders, $fileName){
        $filePath = $this->getParameter('files_directory')."/".$fileName;
        $fp = fopen($filePath, "w");

        foreach ($orders as $data) {
            fputcsv(
                $fp,
                $data,
                ','
            );
        }
        fclose($fp);
        return $filePath;
    }



    /**
     * @Rest\Get("/jsonl")
     */
    public function getJsonlAction()
    {
        $url = "http://localhost:8000/api/orders";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $orders = curl_exec($ch);
        curl_close($ch);
        $orders = json_decode($orders, true);
        $fileName = "out.jsonl";
        $jsonlFile = $this->getParameter('files_directory')."/".$fileName;
        $fp = fopen($jsonlFile, "w");
        foreach ($orders as $order) {
            self::guardedJsonLine($order);
            $jsonLine = json_encode($order, JSON_UNESCAPED_UNICODE)
                . self::LINE_SEPARATOR;
            fputs($fp, $jsonLine);
        }
        fclose($fp);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($jsonlFile);

        return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_OK));
    }

    protected function guardedJsonLine($line)
    {
        if (is_string($line)) {
            $guardedJsonLine = json_decode($line);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidJson('Invalid Json line detected');
            }
            return $guardedJsonLine;
        }
    }

    protected function loadData(){

        $orders = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->findAll();

        if (!$orders) {
            throw $this->createNotFoundException(
                'There is no order yet'
            );
        }

        return $orders;
    }
}
