<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact')]
class ContactController extends AbstractController
{
    #[Route('', name: 'admin_contact_index')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contacts
        ]);
    }

    #[Route('/{id}', name: 'admin_contact_view')]
    public function view(Contact $contact): Response
    {
        return $this->render('admin/contact/view.html.twig', [
            'contact' => $contact,
        ]);
    }
}
