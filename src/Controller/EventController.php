<?php

namespace App\Controller;

use App\Entity\Event;
use DateTime;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController {

    /**
     * List all events
     *
     * @return View
     */
    public function index(Request $request) {
        $name         = $request->query->get('name');
        $id           = $request->query->get('id');
        $searchParams = [];

        if (!empty($name)) {
            $searchParams['title'] = $name;
        }

        if (!empty($id)) {
            $searchParams['id'] = $id;
        }
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy($searchParams);

        return View::create($events, Response::HTTP_OK);
    }

    /**
     * Create a new event
     *
     * @param Request $request
     *
     * @return View
     * @throws \Exception
     */
    public function create(Request $request) {
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

    public function delete(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Event::class)->find($request->get('id'));
        $entityManager->remove($event);
        $entityManager->flush();
        return View::create($event, Response::HTTP_NO_CONTENT);
    }
}
