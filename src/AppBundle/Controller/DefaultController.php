<?php

namespace AppBundle\Controller;

use CarRental\Component\CarsList;
use AppBundle\Form\RentForm;
use AppBundle\Entity\CarRental;
use CarRental\Infrastructure\Payment\UrlcCheck;
use CarRental\Infrastructure\Payment\ConfirmPayment;
use CarRental\Domain\Exception\RentException;
use CarRental\Application\CarRenting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


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

    public function confirmPaymentAction(Request $request)    
    {
        $confirmPayment = new ConfirmPayment();
        $carsService = $this->get('cars_service');
	$carRenting = new CarRenting();

        try {
            $carRenting->completePurchase($carsService,
            $_GET['carId'],
                $confirmPayment->createPayment(
                    new UrlcCheck(
                        $request->request->all()
                    )
                )
            );
 
            return new Response('OK');
        } catch (RentException $e) {
            return new Response('FAIL');
        }
    }
     
    public function succesfulPaymentAction(Request $request) {
        return $this->render('default/success.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}
