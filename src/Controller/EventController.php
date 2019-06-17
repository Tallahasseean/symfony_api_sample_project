<?php

namespace App\Controller;

use App\Entity\Event;
use DateTime;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController
{

    /**
     * List all events
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index()
    {

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $view   = $this->json($events);

        return $view;
    }

    /**
     * Create a new event
     *
     * @param Request $request
     *
     * @return View
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event         = new Event();

        $event->setTitle($request->get('title'));
        $event->setDescription($request->get('description'));
        $event->setStartDate(new DateTime($request->get('startDate')));
        $event->setEndDate(new DateTime($request->get('endDate')));

        $entityManager->persist($event);
        $entityManager->flush();

        return View::create($event, Response::HTTP_CREATED);
    }
}
