<?php

namespace App\Controller;

use App\Repository\PartenaireRepository;
use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageAdminController extends AbstractController
{
    #[Route('/homepage-admin', name: 'app_homepage_admin', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository, StructureRepository $structureRepository): Response
    {

        $getEmail = $this->getUser()->getEmail();

        $numberPartenaire = $partenaireRepository->count([]);
        $numberStructure = $structureRepository->count([]);




        return $this->render('homepage_admin/index.html.twig', [
            'controller_name' => 'HomepageAdminController',
            'email'=>$getEmail,
            'numberPartenaire'=>$numberPartenaire,
            'numberStructure'=>$numberStructure,
            'partenaire' => $partenaireRepository->findBy(
        [],
        [],
        2,
        0),
        ]);
    }
}
