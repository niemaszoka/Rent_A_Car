<?php

namespace AppBundle\Controller;

use CarRental\Component\CarsList;
use AppBundle\Form\RentForm;
use AppBundle\Entity\CarRental;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;


class DefaultController extends Controller
{
    public function entryAction()
    {
        return $this->render('default/entry.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    public function listAction(Request $request)
    {

        $carsService = $this->get('cars_service');
        $cars = $carsService->getAvailableCars();

        return $this->render('default/list.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'cars' => $cars
        ]);
    }

    public function rentFormAction(Request $request)
    {

        $carRental = new CarRental();    
        $form = $this->createForm(RentForm::class, $carRental);

        $form->handleRequest($request);


        $carsService = $this->get('cars_service');

        if ($form->isSubmitted() && $form->isValid()) {

            $formService = $this->get('form_service');
            $formData = $form->getData();

            $resultData = $formService->handleSubmission(
                $form->getData()
            );
            
            var_dump($resultData);

            return $this->render('default/rent_confirmation.html.twig', array(
                'formData' => $resultData,
                'carData' => $carsService->getCar($formData->getCarId())
            ));
        }
 
        $carId = $_GET['carId']; 
        $carsService->bookCar($carId);

        return $this->render('default/rent.html.twig', array(
            'form' => $form->createView(),
            'carData' => $carsService->getCar($carId)
        ));
    }

    public function paymentAction(Request $request) {

    }
}
