<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

#[IsGranted('ROLE_ADMIN')]
class ContactController extends AbstractController {

  #[Route("/create-contact", name: "create_contact")]
  public function create(Request $request, ManagerRegistry $doctrine): Response {
    $contact = new Contact();

    $form = $this->createForm(ContactType::class, $contact);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->persist($contact);
      $em->flush();
      return $this->redirectToRoute('contact_list');
    }

    return $this->renderForm("contact/create.html.twig", [
        "form" => $form
    ]);
  }

  #[Route("/contact-list", name: "contact_list")]
  public function getAll(ManagerRegistry $doctrine): Response {
    $repo = $doctrine->getRepository(Contact::class);
    $contacts = $repo->findAll();

    return $this->renderForm("contact/list.html.twig", [
        "contacts" => $contacts
    ]);
  }

  #[Route("/update-contact/{id}", name: "update_contact")]
  public function update(Request $request, ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $contact = $entityManager->getRepository(Contact::class)->find($id);

    $form = $this->createForm(ContactType::class, $contact);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->flush();
      return $this->redirectToRoute("contact_list");
    }

    return $this->renderForm("contact/create.html.twig", [
        "form" => $form,
        "contact" => $contact
    ]);
  }

  #[Route("/delete-contact/{id}", name: "delete_agent")]
  public function delete(ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $contact = $entityManager->getRepository(Contact::class)->find($id);


    $entityManager->remove($contact);
    $entityManager->flush();

    return $this->redirectToRoute("contact_list");

  }

}