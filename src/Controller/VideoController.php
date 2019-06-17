<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Video;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class VideoController extends AbstractController
{

    public function index(Request $request)
    {
        $name         = $request->query->get('name');
        $eventId      = $request->query->get('eventId');
        $searchParams = [];

        if ( ! empty($name)) {
            $searchParams['title'] = $name;
        }

        if ( ! empty($eventId)) {
            $searchParams['event'] = $eventId;
        }

        $videos = $this->getDoctrine()->getRepository(Video::class)->findBy($searchParams);

        return View::create($videos, Response::HTTP_OK);
    }

    /**
     * Create a new video
     *
     * @param Request $request
     *
     * @return View
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $video         = new Video();

        $video->setTitle($request->get('title'));
        $video->setDescription($request->get('description'));
        $video->setThumbnail($request->get('thumbnail'));
        $video->setPlaylistUrl($request->get('playlistUrl'));

        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get('eventId'));
        $video->setEvent($event);

        $entityManager->persist($video);
        $entityManager->flush();

        return View::create($video, Response::HTTP_CREATED);
    }
}
