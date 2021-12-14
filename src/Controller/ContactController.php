<?php

namespace App\Controller;

use App\Service\NotifyService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contactez-moi', name: 'contact')]
    public function contact(Request $request, NotifyService $notifyService): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $message = $request->request->get('message');

            if ($email && $message) {
                // 1. Envoyer un email à l'admin
                $templatedEmail = (new TemplatedEmail())
                    ->to($this->getParameter('email_website'))
                    ->replyTo($email)
                    ->subject("[Victor] Nouveau message du site")
                    ->htmlTemplate('contact/email/contact.email.twig')
                    ->context([
                        'mail' => $email,
                        'message' => $message,
                    ]);
                $notifyService->sendEmail($templatedEmail);

                // 2. Envoyer un accusé de réception à l'utilisateur
                $templatedEmail = (new TemplatedEmail())
                    ->to($email)
                    ->subject("[Victor] Nous avons reçu votre message")
                    ->htmlTemplate('contact/email/contact_receipt.email.twig')
                    ->context([
                        'mail' => $email,
                        'message' => $message,
                    ]);
                $notifyService->sendEmail($templatedEmail);

                // 3. Afficher un message de succès
                $this->addFlash('success', "Nous avons bien reçu votre message.");

                return $this->redirectToRoute('contact');
            } else {
                $this->addFlash('danger', "Veuillez remplir correctement le formulaire.");
            }
        }

        return $this->render('contact/contact.html.twig', [

        ]);
    }
}
