<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(\Swift_Mailer $mailer)
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/sendmail", name="sendmail")
     */
    public function sendMail(Request $request, \Swift_Mailer $mailer){
        $emailAddress = $request->getContent();
        $jsonName = "out.jsonl";
        $jsonlFile = $this->getParameter('files_directory')."/".$jsonName;
        $csvName = "out.csv";
        $csvFile = $this->getParameter('files_directory')."/".$csvName;
        $message = (new \Swift_Message('Orders Report'))
        ->setFrom('anabella.fairiza@gmail.com')
        ->setTo($emailAddress)
        ->setBody('<p>Dear all,</p>', 'text/html')
        ->addPart('<q>Here is attached some file csv and jsonl.</q>', 'text/html')
        ->attach(\Swift_Attachment::fromPath($csvFile))
        ->attach(\Swift_Attachment::fromPath($jsonlFile));

        $mailer->send($message);

        return ['status'=>'ok'];
    }
}
