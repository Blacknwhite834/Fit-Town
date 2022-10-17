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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function new(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer): Response
    {
        $structure = new Structure();


        $generator = function (
            $length,
            $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ) {
            $str = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            if ($max < 1) {
                throw new Exception('$keyspace must be at least two characters long');
            }
            for ($i = 0; $i < $length; ++$i) {
                $str .= $keyspace[random_int(0, $max)];
            }
            return $str;
        };



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

            $structurePermission->setPermissionPartenaire($partenaire->getPartenairePermission());




            $structure->setIsActive(true);
            $structure->setRoles(['ROLE_STRUCTURE']);
            $structurePermission->setStructure($structure);
            $structure->setPartenaire($partenaire);

            $structure->setPassword($generator(14));
            $email =(new Email()) //envoyer un email à la structure quand elle est créé
            ->from('fit.townpro@gmail.com')
                ->to($structure->getEmail())
                ->subject('Bienvenue chez Fit Town !')
                ->text("Bonjour, vous avez été ajouté en tant que gérant d'une structure chez Fit Town. Vous pouvez vous connecter en insérant votre email et le mot de passe suivant : {$structure->getPassword()}");
            $mailer->send($email);


            $structure->setPassword(
                $userPasswordHasher->hashPassword(
                    $structure,
                    $structure->getPassword()
                ));
            $entityManager->persist($structurePermission);
            $entityManager->flush();
            $structureRepository->add($structure, true);


            $email2 =(new Email()) //envoyer un email à la structure quand elle est créé
            ->from('fit.townpro@gmail.com')
                ->to($partenaire->getEmail())
                ->subject('Une nouvelle structure a été ajoutée !')
                ->text("Bonjour, une nouvelle structure a été ajoutée. Vous pouvez la consulter en vous connectant à votre compte.");
            $mailer->send($email2);
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

    #[Route('/{id}/activate-permission', name: 'app_structure_activate-permission', methods: ['GET', 'POST'])]
    public function activatePermission(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission->setIsMembersRead(!$structurePermission ->isIsMembersRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();



        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission2', name: 'app_structure_activate-permission2', methods: ['GET', 'POST'])]
    public function activatePermission2(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsMembersWrite(!$structurePermission ->isIsMembersWrite()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission3', name: 'app_structure_activate-permission3', methods: ['GET', 'POST'])]
    public function activatePermission3(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsMembersAdd(!$structurePermission ->isIsMembersAdd()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission4', name: 'app_structure_activate-permission4', methods: ['GET', 'POST'])]
    public function activatePermission4(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsMembersProductsAdd(!$structurePermission ->isIsMembersProductsAdd()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission5', name: 'app_structure_activate-permission5', methods: ['GET', 'POST'])]
    public function activatePermission5(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsMembersPaymentSchedulesRead(!$structurePermission ->isIsMembersPaymentSchedulesRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission6', name: 'app_structure_activate-permission6', methods: ['GET', 'POST'])]
    public function activatePermission6(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsMembersStatistiquesRead(!$structurePermission ->isIsMembersStatistiquesRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission7', name: 'app_structure_activate-permission7', methods: ['GET', 'POST'])]
    public function activatePermission7(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsMembersSubscriptionRead(!$structurePermission ->isIsMembersSubscriptionRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission8', name: 'app_structure_activate-permission8', methods: ['GET', 'POST'])]
    public function activatePermission8(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsPaymentSchedulesRead(!$structurePermission ->isIsPaymentSchedulesRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission9', name: 'app_structure_activate-permission9', methods: ['GET', 'POST'])]
    public function activatePermission9(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsPaymentSchedulesWrite(!$structurePermission ->isIsPaymentSchedulesWrite()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission10', name: 'app_structure_activate-permission10', methods: ['GET', 'POST'])]
    public function activatePermission10(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structurePermission = $entityManager->getRepository(StructurePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structurePermission ->setIsPaymentDayRead(!$structurePermission ->isIsPaymentDayRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $email =(new Email()) //envoyer un email à la structure
        ->from('fit.townpro@gmail.com')
            ->to($structurePermission->getStructure()->getEmail(), $structurePermission->getStructure()->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission de votre structure a été modifiée .");
        $mailer->send($email);

        $entityManager->persist($structurePermission );
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission11', name: 'app_structure_activate-permission11', methods: ['GET', 'POST'])]
    public function activatePermission11(EntityManagerInterface $entityManager, Request $request, StructureRepository $structureRepository, MailerInterface $mailer): Response
    {

        $structure = $entityManager->getRepository(Structure::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $structure->setIsActive(($structure->isIsActive())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )

        $entityManager->persist($structure);
        $entityManager->flush();

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
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