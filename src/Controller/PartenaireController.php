<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Entity\PartenairePermission;
use App\Entity\Structure;
use App\Entity\StructurePermission;
use App\Form\PartenairePermissionType;
use App\Form\PartenaireType;
use App\Repository\PartenairePermissionRepository;
use App\Repository\PartenaireRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

#[Route('/partenaire')]
class PartenaireController extends AbstractController
{

    #[Route('/', name: 'app_partenaire_index', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository, Request $request): Response
    {
        $getEmail = $this->getUser()->getEmail();
        $search = $partenaireRepository->findOneBySomeField(
            $request->query->get('p')
        );

        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $search,
            'email'=>$getEmail,
          // 'searchs' => $searchs,
        ]);
    }

    /*#[Route('/browse/{slug}', name: 'app_partenaire_index', methods: ['GET'])]
    public function browse(PartenaireRepository $partenaireRepository, Request $request): Response
    {

        $sort = $partenaireRepository->sort(
            $request->query->get('q')
        );

        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $sort,
         //   'email'=>$getEmail,
            // 'searchs' => $searchs,
        ]);
    }*/

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

    #[Route('/{id}/activate-permission', name: 'app_partenaire_activate-permission', methods: ['GET', 'POST'])]
    public function activatePermission(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);

        $partenairePermission->setIsMembersRead(!$partenairePermission->isIsMembersRead()); // set the value of the permission to the opposite of what it is ( for toggle switch )

        $partenairePermission->getStructurePermission()->setIsMembersRead($partenairePermission->isIsMembersRead());

        $entityManager->persist($partenairePermission);
        $entityManager->flush();

        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission2', name: 'app_partenaire_activate-permission2', methods: ['GET', 'POST'])]
    public function activatePermission2(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


         $partenairePermission->setIsMembersWrite(($partenairePermission->isIsMembersWrite())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsMembersWrite($partenairePermission->isIsMembersWrite());

        $entityManager->persist($partenairePermission);
        $entityManager->flush();

        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission3', name: 'app_partenaire_activate-permission3', methods: ['GET', 'POST'])]
    public function activatePermission3(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);



         $partenairePermission->setIsMembersAdd(($partenairePermission->isIsMembersAdd())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsMembersAdd($partenairePermission->isIsMembersAdd());




        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission4', name: 'app_partenaire_activate-permission4', methods: ['GET', 'POST'])]
    public function activatePermission4(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


        $partenairePermission->setIsMembersProductsAdd(($partenairePermission->isIsMembersProductsAdd())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsMembersProductsAdd($partenairePermission->isIsMembersProductsAdd());



        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission5', name: 'app_partenaire_activate-permission5', methods: ['GET', 'POST'])]
    public function activatePermission5(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


        $partenairePermission->setIsMembersPaymentSchedulesRead(($partenairePermission->isIsMembersPaymentSchedulesRead())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsMembersPaymentSchedulesRead($partenairePermission->isIsMembersPaymentSchedulesRead());



        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission6', name: 'app_partenaire_activate-permission6', methods: ['GET', 'POST'])]
    public function activatePermission6(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


        $partenairePermission->setIsMembersStatistiquesRead(($partenairePermission->isIsMembersStatistiquesRead())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsMembersStatistiquesRead($partenairePermission->isIsMembersStatistiquesRead());



        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission7', name: 'app_partenaire_activate-permission7', methods: ['GET', 'POST'])]
    public function activatePermission7(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


        $partenairePermission->setIsMembersSubscriptionRead(($partenairePermission->isIsMembersSubscriptionRead())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsMembersSubscriptionRead($partenairePermission->isIsMembersSubscriptionRead());




        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission8', name: 'app_partenaire_activate-permission8', methods: ['GET', 'POST'])]
    public function activatePermission8(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


        $partenairePermission->setIsPaymentSchedulesRead(($partenairePermission->isIsPaymentSchedulesRead())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsPaymentSchedulesRead($partenairePermission->isIsPaymentSchedulesRead());




        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission9', name: 'app_partenaire_activate-permission9', methods: ['GET', 'POST'])]
    public function activatePermission9(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);


        $partenairePermission->setIsPaymentSchedulesWrite(($partenairePermission->isIsPaymentSchedulesWrite())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsPaymentSchedulesWrite($partenairePermission->isIsPaymentSchedulesWrite());




        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate-permission10', name: 'app_partenaire_activate-permission10', methods: ['GET', 'POST'])]
    public function activatePermission10(EntityManagerInterface $entityManager, Request $request, PartenaireRepository $partenaireRepository, MailerInterface $mailer): Response
    {

        $partenairePermission = $entityManager->getRepository(PartenairePermission::class)->findOneBy([ // get the id of the partenaire
            'id' => $request->get('id')
        ]);



          $partenairePermission->setIsPaymentDayRead(($partenairePermission->isIsPaymentDayRead())?false:true); // set the value of the permission to the opposite of what it is ( for toggle switch )
        $partenairePermission->getStructurePermission()->setIsPaymentDayRead($partenairePermission->isIsPaymentDayRead());



        // $partenaire->setIsActive(($partenaire->isIsActive())?false:true); // set the value of the partenaire to the opposite of what it is ( for toggle switch )

        $entityManager->persist($partenairePermission);
        $entityManager->flush();
        $email =(new Email()) //envoyer un email au partenaire
        ->from('fit.townpro@gmail.com')
            ->to($partenairePermission->getPartenaire()->getEmail())
            ->subject('Une permission a été modifiée!')
            ->text("Bonjour, une permission globale a été modifiée .");
        $mailer->send($email);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }





    #[Route('/new', name: 'app_partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartenaireRepository $partenaireRepository, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer): Response
    {
        $partenaire = new Partenaire();
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


        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partenaire->setIsActive(true);
            $partenaire->setRoles(['ROLE_PARTENAIRE']);
            $partenaire->setPassword($generator(14));
            $email =(new Email()) //envoyer un email au partenaire quand il est créé
            ->from('fit.townpro@gmail.com')
                ->to($partenaire->getEmail())
                ->subject('Bienvenue chez Fit Town !')
                ->text("Bonjour, vous avez été ajouté en tant que partenaire chez Fit Town. Vous pouvez vous connecter en insérant votre email et le mot de passe suivant : {$partenaire->getPassword()}");
            $mailer->send($email);
            $partenaire->setPassword(
                $userPasswordHasher->hashPassword(
                    $partenaire,
                    $partenaire->getPassword()
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