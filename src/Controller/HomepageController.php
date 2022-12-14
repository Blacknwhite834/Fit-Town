<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Repository\PartenairePermissionRepository;
use App\Repository\StructureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_PARTENAIRE")
 */
class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage', methods: ['GET'])]
    public function index(StructureRepository $structureRepository, PartenairePermissionRepository $partenairePermissionRepository, ): Response
    {

        $getEmail = $this->getUser()->getEmail();


        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'structures'=>$structureRepository->findBy(
                ['is_active' => true, 'partenaire' => $this->getUser()->getId()],
                [],
                2,
                0
            ),
            'permission'=>$partenairePermissionRepository->findBy(
                ['partenaire' => $this->getUser()->getId()],
            ),

            'email'=>$getEmail,
        ]);
    }
}