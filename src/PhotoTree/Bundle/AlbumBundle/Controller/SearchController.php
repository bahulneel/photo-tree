<?php

namespace PhotoTree\Bundle\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;

class SearchController extends Controller
{
    public function queryAction()
    {
        $request = $this->getRequest();
        $query = $request->get('query');
        
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('PhotoTreeAlbumBundle:Name\Name');
        
        $names = $repo->findByFirstName($query);

        $people = new ArrayCollection;
        foreach ($names as $name) {
            $person = $name->getParticipant()->getPerson();
            if (!$people->contains($person)) {
                $people->add($person);
            }
        }
        
        return $this->render('PhotoTreeAlbumBundle:Search:query.html.twig', array(
            'query' => $query,
            'people' => $people
        ));
    }
}
