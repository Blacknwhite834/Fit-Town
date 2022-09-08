<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Entity\PartenairePermission;
use App\Form\PartenairePermissionType;
use App\Form\PartenaireType;
use App\Repository\PartenaireRepository;
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
        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartenaireRepository $partenaireRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
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
        ]);
    }

    #[Route('/{id}', name: 'app_partenaire_show', methods: ['GET'])]
    public function show(Partenaire $partenaire): Response
    {
        return $this->render('partenaire/show.html.twig', [
            'partenaire' => $partenaire,
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