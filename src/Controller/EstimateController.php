<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Form\Type\EstimateType;
use App\Repository\EstimateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

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

    /**
     * @Route("/estimate/{estimate}/update", name="estimate_part_update")
     */
    public function estimatePartUpdate(Estimate $estimate, Request $request): Response
    {
        $key = $request->request->get('key');
        $value = $request->get('value');

        if (!$key) {
            throw new \RuntimeException('Missing required parameter: key');
        }

        if (!$value) {
            return new Response('Missing value', 400);
        }

        dd($key, $value);
    }

    /**
     * @Route("/estimate/{estimate}/json", name="estimate_view_json", format="json")
     */
    public function estimateDetailsJson(Estimate $estimate, SerializerInterface $serializer): JsonResponse
    {
        $ret = JsonResponse::fromJsonString($serializer->serialize($estimate, 'json', ['groups' => ['estimate_read']]));
        $ret->setEncodingOptions($ret->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $ret;
    }

    /**
     * @Route("/estimate/{estimate}", name="estimate_view", format="html")
     */
    public function viewEstimate(Estimate $estimate, RouterInterface $router): Response
    {
        return $this->render(
            'estimate/entry.html.twig',
            [
                'estimate' => $estimate,
                'estimateJsonRoute' => $router->generate('estimate_view_json', ['estimate' => $estimate->getId()]),
                'estimatePartUpdateRoute' => $router->generate('estimate_part_update', ['estimate' => $estimate->getId()]),
            ]
        );
    }
}
