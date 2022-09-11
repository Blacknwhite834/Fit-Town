<?php

namespace App\Controller;

use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructurePageController extends AbstractController
{
    #[Route('/structure-page', name: 'app_structure_page', methods: ['GET'])]
    public function index(StructureRepository $structureRepository): Response
    {

        $getName = $this->getUser()->getName();
        $getEmail = $this->getUser()->getEmail();

        return $this->render('structure_page/index.html.twig', [
            'controller_name' => 'StructurePageController',
            'name'=>$getName,
            'email'=>$getEmail,
            'structures'=>$structureRepository->findBy(
                ['is_active' => true, 'partenaire' => $this->getUser()->getId()],
                [],
            ),
        ]);
    }
}
