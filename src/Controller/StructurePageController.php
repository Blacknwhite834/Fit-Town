<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_PARTENAIRE")
 */
class StructurePageController extends AbstractController
{
    #[Route('/structure-page', name: 'app_structure_page', methods: ['GET'])]
    public function index(StructureRepository $structureRepository,  Request $request, EntityManagerInterface $entityManager): Response
    {
        $partenaireId = $entityManager->getRepository(Partenaire::class)->findOneBy([
            'id' => $this->getUser()->getId(),
        ]);

        $getName = $this->getUser()->getName();
        $getEmail = $this->getUser()->getEmail();

        $search2 = $structureRepository->findOneBySomeField2(
            $partenaireId,
            $request->query->get('q')
        );

        return $this->render('structure_page/index.html.twig', [
            'controller_name' => 'StructurePageController',
            'name'=>$getName,
            'email'=>$getEmail,
            'structures'=>$search2,

        ]);
    }
}
