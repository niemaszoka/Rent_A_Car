<?php

namespace AppBundle\Controller;

use CarRental\Component\CarsList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CarRental\Application\RentForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


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

        if ($request->request->has('car'))
        {
            $this->goToFormAction($request);
        }

        $carsService = $this->get('cars_service');
        $cars = $carsService->getAvailableCars();

        return $this->render('default/list.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'cars' => $cars
        ]);
    }

    public function goToFormAction(Request $request)
    {
        $session = $this->get('session');
        $session->set('key', $request->get('car'));
        $router = $this->get('router');
        $url = $router->generate('rent');    
        return new RedirectResponse(
            $url
        );
    }

    public function rentCarAction(Request $request)
    {
        return $this->render('default/form.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..')
        ]);
    }

    public function rentFormAction(Request $request)
    {
        $Car=$_GET['CarID'];
        $rentForm = new RentForm();
        $rentForm->setCarID($_GET['CarID']);
        $rentForm->setBrand($_GET['Brand']);
        $rentForm->setSegment($_GET['Segment']);
        $rentForm->setPricePerDay($_GET['PricePerDay']);

        $form = $this->createFormBuilder($rentForm)
            ->add('CarID', TextType::class)
            ->add('Brand', TextType::class)
            ->add('Segment', TextType::class)
            ->add('PricePerDay', TextType::class)
            ->add('RENT', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $Brand=$_GET['Brand'];
            $ID=$_GET['CarID'];
  
            $file = dirname(__FILE__).'/Rented';
            $json = json_decode(file_get_contents($file), true);
            $json[$Brand] = array("rented"=>true);
            file_put_contents($file, json_encode($json));
            return $this->redirect($this->generateUrl('list'));
        }

        return $this->render('default/rent.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
