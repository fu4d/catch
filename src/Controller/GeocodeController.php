<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Geocode;

class GeocodeController extends FOSRestController
{
    /**
     * @Route("/geocode", name="geocode")
     */
    public function index()
    {
        return $this->render('geocode/index.html.twig', [
            'controller_name' => 'GeocodeController',
        ]);
    }


    /**
     * @Rest\Get("/coordinate/{postcode}")
     */
    public function getLoglatAction($postcode)
    {
        $geocode = $this->getDoctrine()
            ->getRepository(Geocode::class)
            ->findBy(['postcode' => $postcode]);

        if (!$geocode) {
            throw $this->createNotFoundException(
                'There is no data with the following postcode: ' . $postcode
            );
        }

        return $this->handleView($this->view($geocode));
    }
}
