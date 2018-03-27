<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
        //$em = $this->getDoctrine()->getManager();
        //$user_repo = $em->getRepository('BackendBundle:User');
        //$user = $user_repo->find(1);

        $user = $this->getDoctrine()->getRepository('BackendBundle:User')->find(1);

        $fullname = $user->getFirstname() . " " . $user->getLastname();

        return $this->render('@Backend/Default/index.html.twig', ['user' => $user, 'fullname' => $fullname]);
    }
}
