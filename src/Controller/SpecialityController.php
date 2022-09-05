<?php

namespace App\Controller;

use App\Entity\Speciality;
use App\Form\SpecialityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

#[IsGranted('ROLE_ADMIN')]
class SpecialityController extends AbstractController {

  #[Route("/create-speciality", name: "create_speciality")]
  public function create(Request $request, ManagerRegistry $doctrine): Response {
    $speciality = new Speciality();

    $form = $this->createForm(SpecialityType::class, $speciality);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->persist($speciality);
      $em->flush();
      return $this->redirectToRoute('speciality_list');
    }

    return $this->renderForm("speciality/create.html.twig", [
        "form" => $form
    ]);
  }

  #[Route("/speciality-list", name: "speciality_list")]
  public function getAll(ManagerRegistry $doctrine): Response {
    $repo = $doctrine->getRepository(Speciality::class);
    $specialities = $repo->findAll();

    return $this->renderForm("speciality/list.html.twig", [
        "specialities" => $specialities
    ]);
  }

  #[Route("/update-speciality/{id}", name: "update_speciality")]
  public function update(Request $request, ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $speciality = $entityManager->getRepository(speciality::class)->find($id);

    $form = $this->createForm(SpecialityType::class, $speciality);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->flush();
      return $this->redirectToRoute("speciality_list");
    }

    return $this->renderForm("speciality/create.html.twig", [
        "form" => $form,
        "speciality" => $speciality
    ]);
  }

  #[Route("/delete-speciality/{id}", name: "delete_speciality")]
  public function delete(ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $speciality = $entityManager->getRepository(speciality::class)->find($id);


    $entityManager->remove($speciality);
    $entityManager->flush();

    return $this->redirectToRoute("speciality_list");

  }

}
