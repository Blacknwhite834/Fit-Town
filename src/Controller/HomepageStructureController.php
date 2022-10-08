<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Repository\StructurePermissionRepository;
use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageStructureController extends AbstractController
{
    #[Route('/homepage-structure', name: 'app_homepage_structure', methods: ['GET', 'POST'])]
    public function index(StructurePermissionRepository $structurePermissionRepository, StructureRepository $structureRepository): Response
    {
        $getEmail = $this->getUser()->getEmail();



        return $this->render('homepage_structure/index.html.twig', [
            'controller_name' => 'HomepageStructureController',
            'email'=>$getEmail,
            'structures'=>$structureRepository->findBy(
                ['email' => $this->getUser()->getEmail()],
            ),
            'permissions'=>$structurePermissionRepository->findBy(
                ['Structure' => $this->getUser()->getId()],
                [],
            ),

        ]);
    }
}
