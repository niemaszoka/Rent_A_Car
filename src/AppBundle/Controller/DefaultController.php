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


        if ($form->isSubmitted() && $form->isValid() && $form->get('confirm')->isClicked()) {
            $formData = $form->getData();

            $resultData = $formService->handleSubmission(
                $form->getData()
            );

            $session = $this->get('session');
            $session->set('formData', $resultData);

            $router = $this->get('router');
            $url = $router->generate('confirm_data');
            
            return new RedirectResponse(
                $url
            );
        }

        $carId = $_GET['carId'];

        return $this->render('default/rent_form.html.twig', array(
            'form' => $form->createView(),
            'carData' => $carsService->getCar($carId)
        ));
    }

    public function confirmDataAction(){
        $session = $this->get('session');
        $formData = $session->get('formData');

        return $this->render('default/rent_confirmation.html.twig', array(
            'formData' => $formData
        ));
    }

    public function paymentAction(){
            $session = $this->get('session');
            $formData = $session->get('formData'); 

            $carsService = $this->get('cars_service');
            $carsService->bookCar($formData->getCarId());

            $params = array(
                'id' => '721088',
                'amount' => $formData->getTotalAmount(),
                'description' => "Oplata za wypozyczenie ".$formData->getCarName(),
                'URL' => 'http://v-ie.uek.krakow.pl/~s180277/app_dev.php/succesful_payment',
                'URLC' => 'http://v-ie.uek.krakow.pl/~s180277/app_dev.php/confirm_payment?'.$formData->getCarId(),
                'control' => $formData->getCarId(),
                'firstname' => $formData->getFirstName(),
                'lastname' => $formData->getLastName(),
                'email' => $formData->getEmail(),
                'type' => 3,
                'api_version' => 'dev',
            );
            $url = sprintf(
                '%s?%s',
                'https://ssl.dotpay.pl/test_payment/',
                http_build_query($params)
            );
            return new RedirectResponse($url);
    }

    public function confirmPaymentAction(Request $request)    
    {
        $paymentFactory = new PaymentFactory();
        $paymentService = $this->get('payment_service');

        try {
            $paymentService->completePurchase(
                $PaymentFactory->createPayment(
                    new CompletePayment(
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
        $session = $this->get('session');
        $data = $session->get('formData');
        $carsService = $this->get('cars_service');
        $usersService = $this->get('users_service');
        $carsService->rentCar($data->getCarId());
        $usersService->rentCar($data);

        return $this->render('default/succesfull_payment.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}
