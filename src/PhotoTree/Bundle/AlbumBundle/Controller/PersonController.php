<?php

namespace PhotoTree\Bundle\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PersonController extends Controller
{
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PhotoTreeAlbumBundle:Person');
        
        $person = $repo->find($id);
        if (!$person) {
            throw $this->createNotFoundException('Person not found');
        }
        return $this->render('PhotoTreeAlbumBundle:Person:view.html.twig', array(
            'person' => $person
        ));
    }
}
