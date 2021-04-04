<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function list(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/users/{id<\d+>}", name="user_show")
     * @param int $id
     * @return Response
     */
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['id' => $id]);
        if (!$user) {
            throw $this->createNotFoundException(sprintf('No user found for id "%d"', $id));
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/new", name="user_new")
     */
    public function new(LoggerInterface $logger, EntityManagerInterface $entityManager, Request $request)
    {
       $form = $this->createForm(UserFormType::class);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {

           /**  @var User $user */
           $user = $form->getData();
           $user
               ->setActive(User::ACTIVE_YES)
           ;
           $entityManager->persist($user);
           $entityManager->flush();

           $this->addFlash('success', sprintf('L\'utilisateur %s %s a été ajouté avec succès.',
               $user->getFirstName(),
               $user->getLastname()
           ));

           $logger->info(sprintf(
               'New user successfully created ! Its database ID is: #%d',
               $user->getId()
           ));

           return $this->redirectToRoute('homepage');

       }

       return $this->render('user_admin/new.html.twig', [
           'userForm' => $form->createView()
       ]);

    }

    /**
     * @Route("/users/{id<\d+>}/del", name="user_remove")
     * @param int $id
     * @return Response
     */
    public function remove(int $id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['id' => $id]);
        if (!$user) {
            throw $this->createNotFoundException(sprintf('No user found for id "%d"', $id));
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
