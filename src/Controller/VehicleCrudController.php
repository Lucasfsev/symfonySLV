<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vehicle/crud')]
final class VehicleCrudController extends AbstractController
{
    #[Route(name: 'app_vehicle_crud_index', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        return $this->render('vehicle_crud/index.html.twig', [
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vehicle_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicle_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicle_crud/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicle_crud_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('vehicle_crud/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vehicle_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicle_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicle_crud/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicle_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicle_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
