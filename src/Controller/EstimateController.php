<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Form\Type\EstimateType;
use App\Repository\EstimateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstimateController extends AbstractController
{
    /**
     * @Route("/estimates", name="view_all_estimates")
     * @param EstimateRepository $estimateRepository
     *
     * @return Response
     */
    public function index(EstimateRepository $estimateRepository): Response
    {
        return $this->render(
            'estimate/index.html.twig',
            [
                'estimates' => $estimateRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/estimate/create", name="estimate_create")
     * @param Request                $request
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function createNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $estimate = new Estimate();
        $estimate->setDefaultRate($this->getParameter('app.defaults.rate'));
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();
        }
        return $this->render(
            'estimate/estimate.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
