<?php

namespace App\Controller;

use App\Repository\PartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageAdminController extends AbstractController
{
    #[Route('/homepage-admin', name: 'app_homepage_admin', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository): Response
    {

        $getEmail = $this->getUser()->getEmail();


        return $this->render('homepage_admin/index.html.twig', [
            'controller_name' => 'HomepageAdminController',
            'email'=>$getEmail,
            'partenaire' => $partenaireRepository->findBy(
        [],
        [],
        2,
        0),
        ]);
    }
}
