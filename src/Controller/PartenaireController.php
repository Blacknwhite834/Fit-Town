<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Entity\PartenairePermission;
use App\Form\PartenairePermissionType;
use App\Form\PartenaireType;
use App\Repository\PartenairePermissionRepository;
use App\Repository\PartenaireRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaire')]
class PartenaireController extends AbstractController
{
    #[Route('/', name: 'app_partenaire_index', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        $getEmail = $this->getUser()->getEmail();

        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
            'email'=>$getEmail,
        ]);
    }

    #[Route('/{id}/activate', name: 'app_partenaire_activate', methods: ['GET', 'POST'])]
    public function activate(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository): Response
    {

        $partenaire = $entityManager->getRepository(Partenaire::class)->findOneBy([ // get the id of the partenaire
        'id' => $request->get('id')
        ]);



        $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartenaireRepository $partenaireRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $getEmail = $this->getUser()->getEmail();

        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partenaire->setIsActive(true);
            $partenaire->setRoles(['ROLE_PARTENAIRE']);
            $partenaire->setPassword(
                $userPasswordHasher->hashPassword(
                    $partenaire,
                    $form->get('plainPassword')->getData()
                ));
            $partenaireRepository->add($partenaire, true);

            return $this->redirectToRoute('app_partenaire_permission', ['id'=>$partenaire->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
            'email'=>$getEmail,
        ]);
    }

    #[Route('/{id}/permission', name: 'app_partenaire_permission', methods: ['GET', 'POST'])] // id of the newly created post /permission
    public function permission(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partenairePermission = new PartenairePermission();
        $form = $this->createForm(PartenairePermissionType::class, $partenairePermission);
        $form->handleRequest($request);
        $partenaire = $entityManager->getRepository(Partenaire::class)->findOneBy([ // get the newly created partenaire id
            'id' => $request->get('id')
        ]);

        $getEmail = $this->getUser()->getEmail();


        if ($form->isSubmitted() && $form->isValid()) {
            $partenairePermission->setPartenaire($partenaire);
            $entityManager->persist($partenairePermission);
            $entityManager->flush();
            return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('partenaire/permission.html.twig', [
            'partenaire' => $partenaire,
            'partenairePermission' => $partenairePermission,
            'form' => $form->createView(),
            'email'=>$getEmail,
        ]);
    }

    #[Route('/{id}', name: 'app_partenaire_show', methods: ['GET', 'POST'])]
    public function show(Partenaire $partenaire, EntityManagerInterface $entityManager, Request $request, PartenairePermissionRepository $partenairePermissionRepository, StructureRepository $structureRepository): Response
    {
        $getEmail = $this->getUser()->getEmail();
        $partenaireId = $entityManager->getRepository(Partenaire::class)->findOneBy([ // get the newly created partenaire id
            'id' => $request->get('id')
        ]);


        return $this->render('partenaire/show.html.twig', [
            'partenaire' => $partenaire,
            'permission'=>$partenairePermissionRepository->findBy(
                ['partenaire' => $partenaireId],
            ),
            'structures'=>$structureRepository->findBy(
                ['is_active' => true, 'partenaire' => $partenaireId],
                [],
            ),
            'email'=>$getEmail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_partenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partenaire $partenaire, PartenaireRepository $partenaireRepository): Response
    {
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partenaireRepository->add($partenaire, true);

            return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partenaire_delete', methods: ['POST'])]
    public function delete(Request $request, Partenaire $partenaire, PartenaireRepository $partenaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->request->get('_token'))) {
            $partenaireRepository->remove($partenaire, true);
        }

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }
}