<?php

namespace AppBundle\Controller;

use CarRental\Component\CarsList;
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
}
