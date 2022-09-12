<?php

namespace App\Controller;

use App\Entity\Hideout;
use App\Form\HideoutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

#[IsGranted('ROLE_ADMIN')]
class HideoutController extends AbstractController {

  #[Route("/create-hideout", name: "create_hideout")]
  public function create(Request $request, ManagerRegistry $doctrine): Response {
    $hideout = new Hideout();

    $form = $this->createForm(HideoutType::class, $hideout);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->persist($hideout);
      $em->flush();
      return $this->redirectToRoute('hideout_list');
    }

    return $this->renderForm("hideout/create.html.twig", [
        "form" => $form
    ]);
  }

  #[Route("/hideout-list", name: "hideout_list")]
  public function getAll(ManagerRegistry $doctrine): Response {
    $repo = $doctrine->getRepository(Hideout::class);
    $hideouts = $repo->findAll();

    return $this->renderForm("hideout/list.html.twig", [
        "hideouts" => $hideouts
    ]);
  }

  #[Route("/update-hideout/{id}", name: "update_hideout")]
  public function update(Request $request, ManagerRegistry $doctrine, Hideout $hideout): Response {

   $form = $this->createForm(HideoutType::class, $hideout);
   $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $doctrine->getManager()->flush();
      $this->addFlash('error', 'Planque modifiée');
      return $this->redirectToRoute("hideout_list");
    }

    return $this->renderForm("hideout/create.html.twig", [
        "form" => $form,
        "hideout" => $hideout
    ]);
  }

  #[Route("/delete-hideout/{id}", name: "delete_hideout")]
  public function delete(ManagerRegistry $doctrine, Hideout $hideout): Response {

    $em = $doctrine->getManager();
    $em->remove($hideout);
    $em->flush();
    $this->addFlash('error', 'Planque supprimée');
    return $this->redirectToRoute("hideout_list");

  }
  
}