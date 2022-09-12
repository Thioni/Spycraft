<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;

#[IsGranted('ROLE_ADMIN')]
class AgentController extends AbstractController {

  #[Route("/create-agent", name: "create_agent")]
  public function create(Request $request, ManagerRegistry $doctrine): Response {
    $agent = new Agent();

    $form = $this->createForm(AgentType::class, $agent);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $doctrine->getManager();
      $em->persist($agent);
      $em->flush();
      return $this->redirectToRoute("agent_list");
    }

    return $this->renderForm("agent/create.html.twig", [
        "form" => $form
    ]);
  }

  #[Route("/agent-list", name: "agent_list")]
  public function getAll(ManagerRegistry $doctrine): Response {
    $repo = $doctrine->getRepository(Agent::class);
    $agents = $repo->findAll();

    return $this->renderForm("agent/list.html.twig", [
        "agents" => $agents
    ]);
  }

  #[Route("/update-agent/{id}", name: "update_agent")]
  public function update(Request $request, ManagerRegistry $doctrine, Agent $agent): Response {

    $form = $this->createForm(AgentType::class, $agent);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $doctrine->getManager()->flush();
      $this->addFlash('error', 'Agent modifié');
      return $this->redirectToRoute("agent_list");
    }

    return $this->renderForm("agent/create.html.twig", [
        "form" => $form,
        "agent" => $agent
    ]);
  }

  #[Route("/delete-agent/{id}", name: "delete_agent")]
  public function delete(ManagerRegistry $doctrine, Agent $agent): Response {

    $em = $doctrine->getManager();
    $em->remove($agent);
    $em->flush();
    $this->addFlash('error', 'Agent supprimé');
    return $this->redirectToRoute("agent_list");
  }

}