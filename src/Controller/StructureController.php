<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Entity\Structure;
use App\Entity\StructurePermission;
use App\Form\StructureType;
use App\Repository\StructurePermissionRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/structure')]
class StructureController extends AbstractController
{
    #[Route('/', name: 'app_structure_index', methods: ['GET'])]
    public function index(StructureRepository $structureRepository): Response
    {
        return $this->render('structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_structure_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $getEmail = $this->getUser()->getEmail();


        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);
        $partenaire = $entityManager->getRepository(Partenaire::class)->findOneBy([
            'id' => $request->get('id')
        ]);


        $structurePermission = new StructurePermission();

        if ($form->isSubmitted() && $form->isValid()) {

            $structurePermission->setIsMembersRead($partenaire->getPartenairePermission()->isIsMembersRead());
            $structurePermission->setIsMembersWrite($partenaire->getPartenairePermission()->isIsMembersWrite());
            $structurePermission->setIsMembersAdd($partenaire->getPartenairePermission()->isIsMembersAdd());
            $structurePermission->setIsMembersProductsAdd($partenaire->getPartenairePermission()->isIsMembersProductsAdd());
            $structurePermission->setIsMembersPaymentSchedulesRead($partenaire->getPartenairePermission()->isIsMembersPaymentSchedulesRead());
            $structurePermission->setIsMembersStatistiquesRead($partenaire->getPartenairePermission()->isIsMembersStatistiquesRead());
            $structurePermission->setIsMembersSubscriptionRead($partenaire->getPartenairePermission()->isIsMembersSubscriptionRead());
            $structurePermission->setIsPaymentSchedulesRead($partenaire->getPartenairePermission()->isIsPaymentSchedulesRead());
            $structurePermission->setIsPaymentSchedulesWrite($partenaire->getPartenairePermission()->isIsPaymentSchedulesWrite());
            $structurePermission->setIsPaymentDayRead($partenaire->getPartenairePermission()->isIsPaymentDayRead());




            $structure->setIsActive(true);
            $structure->setRoles(['ROLE_STRUCTURE']);
            $structurePermission->setStructure($structure);
            $structure->setPartenaire($partenaire);
            $structure->setPassword(
                $userPasswordHasher->hashPassword(
                    $structure,
                    $form->get('plainPassword')->getData()
                ));
            $entityManager->persist($structurePermission);
            $entityManager->flush();
            $structureRepository->add($structure, true);

            return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form,
            'email' => $getEmail,
        ]);
    }

    #[Route('/{id}', name: 'app_structure_show', methods: ['GET'])]
    public function show(Structure $structure, StructurePermissionRepository $structurePermissionRepository): Response
    {


        $getEmail = $this->getUser()->getEmail();

        return $this->render('structure/show.html.twig', [
            'structure' => $structure,
            'permissions'=>$structurePermissionRepository->findBy(
                ['Structure' => $structure->getId()],
                [],
            ),

            'email'=>$getEmail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_structure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Structure $structure, StructureRepository $structureRepository): Response
    {
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $structureRepository->add($structure, true);

            return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('structure/edit.html.twig', [
            'structure' => $structure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_structure_delete', methods: ['POST'])]
    public function delete(Request $request, Structure $structure, StructureRepository $structureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$structure->getId(), $request->request->get('_token'))) {
            $structureRepository->remove($structure, true);
        }

        return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}