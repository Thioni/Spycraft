<?php

namespace App\Controller;

use App\Entity\Target;
use App\Form\TargetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

#[IsGranted('ROLE_ADMIN')]
class TargetController extends AbstractController {

  #[Route("/create-target", name: "create_target")]
  public function create(Request $request, ManagerRegistry $doctrine): Response {
    $target = new Target();

    $form = $this->createForm(TargetType::class, $target);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->persist($target);
      $em->flush();
      return $this->redirectToRoute('target_list');
    }

    return $this->renderForm("target/create.html.twig", [
        "form" => $form
    ]);
  }

  #[Route("/target-list", name: "target_list")]
  public function getAll(ManagerRegistry $doctrine): Response {
    $repo = $doctrine->getRepository(Target::class);
    $targets = $repo->findAll();

    return $this->renderForm("target/list.html.twig", [
        "targets" => $targets
    ]);
  }

  #[Route("/update-target/{id}", name: "update_target")]
  public function update(Request $request, ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $target = $entityManager->getRepository(Target::class)->find($id);

    $form = $this->createForm(TargetType::class, $target);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->flush();
      return $this->redirectToRoute("target_list");
    }

    return $this->renderForm("target/create.html.twig", [
        "form" => $form,
        "target" => $target
    ]);
  }

  #[Route("/delete-target/{id}", name: "delete_target")]
  public function delete(ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $target = $entityManager->getRepository(Target::class)->find($id);


    $entityManager->remove($target);
    $entityManager->flush();

    return $this->redirectToRoute("target_list");

  }

}