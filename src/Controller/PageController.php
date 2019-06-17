<?php
namespace App\Controller;

use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    public function index()
    {

        $view = View::create(['test' => 123], Response::HTTP_OK);

        return $view;
    }
}