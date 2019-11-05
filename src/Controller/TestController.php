<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
//use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Orders;
use App\Form\OrdersType;
use Aws\Sdk;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('anabella.fairiza@gmail.com')
        ->setTo('fuad.abd.j@gmail.com')
        ->setBody(
            $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'orders' => [] //$orderCollections[0],
        ]),
            'text/html'
        );

        $mailer->send($message);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }


}
