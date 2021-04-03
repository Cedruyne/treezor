<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function list(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/utilisateur/{id<\d+>}", name="user_show")
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        dump($this);
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $id,
        ]);
    }

    public function add(LoggerInterface $logger)
    {
        $logger->info();
    }
}
