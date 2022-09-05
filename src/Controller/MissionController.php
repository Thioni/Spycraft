<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Hideout;
use App\Entity\Mission;
use App\Form\MissionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

class MissionController extends AbstractController {

  #[IsGranted('ROLE_ADMIN')]
  #[Route("/create-mission", name: "create_mission")]
  public function create(Request $request, ManagerRegistry $doctrine): Response {
    $mission = new Mission();

    $form = $this->createForm(MissionType::class, $mission);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      if (!$mission->targetsNationalityCheck()) {
        $this->addFlash('error', 'Mission invalide: Un agent ne peut avoir la même nationalité qu\'une cible');
        return $this->redirectToRoute('mission_list');
      } else if (!$mission->contactsNationalityCheck()) {
        $this->addFlash('error', 'Mission invalide: Un contact doit être de la nationalité du pays de la mission');
        return $this->redirectToRoute('mission_list');
      } else if (!$mission->hideoutsCountryCheck()) {
        $this->addFlash('error', 'Mission invalide: Une planque doit se situer dans le pays de la mission');
        return $this->redirectToRoute('mission_list');
      } else if (!$mission->specialityCheck()) {
        $this->addFlash('error', 'Mission invalide: Au moins un agent doit avoir la spécialité de la mission');
        return $this->redirectToRoute('mission_list');
      }

      $em = $doctrine->getManager();
      $em->persist($mission);
      $em->flush();
      return $this->redirectToRoute('mission_list');
    }

    return $this->renderForm("mission/create.html.twig", [
        "form" => $form
    ]);
  }

  #[Route("/", name: "mission_list")]
  public function getAll(ManagerRegistry $doctrine): Response {
    $repo = $doctrine->getRepository(Mission::class);
    $missions = $repo->findAll();

    return $this->renderForm("mission/list.html.twig", [
        "missions" => $missions
    ]);
  }

  #[IsGranted('ROLE_ADMIN')]
  #[Route("/mission-details/{id}", name: "mission_details")]
  public function getDetails(ManagerRegistry $doctrine, int $id): Response {

    $repo = $doctrine->getRepository(Mission::class);
    $mission = $repo->find($id);

    return $this->renderForm("mission/details.html.twig", [
        "mission" => $mission
    ]);
  }

  #[IsGranted('ROLE_ADMIN')]
  #[Route("/update-mission/{id}", name: "update_mission")]
  public function update(Request $request, ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $mission = $entityManager->getRepository(Mission::class)->find($id);

    $form = $this->createForm(MissionType::class, $mission);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      if (!$mission->targetsNationalityCheck()) {
        $this->addFlash('error', 'Mission invalide: Un agent ne peut avoir la même nationalité qu\'une cible');
        return $this->redirectToRoute('mission_list');
      } else if (!$mission->contactsNationalityCheck()) {
        $this->addFlash('error', 'Mission invalide: Un contact doit être de la nationalité du pays de la mission');
        return $this->redirectToRoute('mission_list');
      } else if (!$mission->hideoutsCountryCheck()) {
        $this->addFlash('error', 'Mission invalide: Une planque doit se situer dans le pays de la mission');
        return $this->redirectToRoute('mission_list');
      } else if (!$mission->specialityCheck()) {
        $this->addFlash('error', 'Mission invalide: Au moins un agent doit avoir la spécialité de la mission');        
        return $this->redirectToRoute('mission_list');
      }

      $em = $doctrine->getManager();
      $em->flush();
      $this->addFlash('error', 'Mission modifiée');
      return $this->redirectToRoute("mission_list");
    }

    return $this->renderForm("mission/create.html.twig", [
        "form" => $form,
        "mission" => $mission
    ]);
  }

  #[IsGranted('ROLE_ADMIN')]
  #[Route("/delete-mission/{id}", name: "delete_mission")]
  public function delete(ManagerRegistry $doctrine, int $id): Response {

    $entityManager = $doctrine->getManager();
    $mission = $entityManager->getRepository(Mission::class)->find($id);
    $agentsMission = $entityManager->getRepository(Agent::class)->findBy(array('mission' => $id));
      foreach ($agentsMission as $agentMission) {
        $agentMission->resetMission();
      }
    $hideoutsMission = $entityManager->getRepository(Hideout::class)->findBy(array('mission' => $id));
      foreach ($hideoutsMission as $agentMission) {
        $agentMission->resetMission();
      }
    $entityManager->remove($mission);
    $entityManager->flush();

    return $this->redirectToRoute("mission_list");
  }

}