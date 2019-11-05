<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Orders;
use App\Form\OrdersType;

class OrdersController extends FOSRestController
{
    /**
     * @Route("/orders", name="orders")
     */
    public function index()
    {
        return [
        	"message"	=> "haloo this is test order controller only",
        	"path"		=> "src/Controller/OrdersController.php"
        ];
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
            //->find($id);
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

        //$form->handleRequest($request);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            //$order = $form->getData();
        	$em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_OK));
        }

        return $this->handleView($this->view($form->getErrors()));
    }
}
