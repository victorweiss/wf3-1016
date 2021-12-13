<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contactez-moi', name: 'contact')]
    public function contact(Request $request): Response
    {

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $message = $request->request->get('message');
            // dd($email, $message);

            if ($email && $message) {
                // 1. Envoyer un email à l'admin
                // 2. Envoyer un accusé de réception à l'utilisateur
                // 3. Afficher un message de succès
                $this->addFlash('success', "Nous avons bien reçu votre message.");
            } else {
                $this->addFlash('danger', "Veuillez remplir correctement le formulaire.");
            }
        }

        return $this->render('contact/contact.html.twig', [

        ]);
    }
}
