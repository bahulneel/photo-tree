<?php

namespace PhotoTree\Bundle\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PhotoTreeAlbumBundle:Default:index.html.twig');
    }
}
