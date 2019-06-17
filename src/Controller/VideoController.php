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

    public function index()
    {
        $videos = $this->getDoctrine()->getRepository(Video::class)->findAll();
        $view   = $this->json($videos);

        return $view;
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
