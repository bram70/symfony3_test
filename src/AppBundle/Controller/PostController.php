<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/post/create", name="post_create")
     */
    public function createAction(Request $request)
    {
        return $this->render('post/create.html.twig');
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     */
    public function editAction($id, Request $request)
    {
        return $this->render('post/edit.html.twig');
    }

    /**
     * @Route("/", name="post_list")
     */
    public function listAction()
    {
        return $this->render('post/index.html.twig');
    }
}