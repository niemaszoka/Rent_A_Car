<?php

namespace AppBundle\Controller;

use CarRental\Component\CarsList;
use AppBundle\Form\RentForm;
use AppBundle\Entity\CarRental;
use CarRental\Infrastructure\Payment\ComletePayment;
use CarRental\Infrastructure\Payment\PaymentFactory;
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

        $carsService = $this->get('cars_service');
        $formService = $this->get('form_service');

        $carRental = new CarRental();    
        $form = $this->createForm(RentForm::class, $carRental);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            $resultData = $formService->handleSubmission(
                $form->getData()
            );

            return $this->render('default/rent_confirmation.html.twig', array(
                'formData' => $resultData,
                'carData' => $carsService->getCar($formData->getCarId())
            ));
        }
 
        $carId = $_GET['carId']; 
        $carsService->bookCar($carId);

        return $this->render('default/rent_form.html.twig', array(
            'form' => $form->createView(),
            'carData' => $carsService->getCar($carId)
        ));
    }

    public function confirmPaymentAction(Request $request)    
    {
        $paymentFactory = new PaymentFactory();
        $carsService = $this->get('cars_service');
        $paymentService = $this->get('payment_service');

        try {
            $paymentService->completePurchase(
                $carsService,
                $_GET['carId'],
                $PaymentFactory->createPayment(
                    new ComletePayment(
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
        return $this->render('default/succesfull_payment.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}
