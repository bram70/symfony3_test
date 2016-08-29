<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntryController extends Controller
{
    /**
     * @Route("/entry/create", name="entry_create")
     */
    public function createAction(Request $request)
    {

        $user = $this->getUser();
        $entry = new Entry;

        $entry->setAuthor($user);
        $form = $this->createFormBuilder($entry)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                ->add('content', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                ->add('author', HiddenType::class )
                ->add('save', SubmitType::class, array(
                                                'label' => 'Create Entry',
                                                'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                ->getForm();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $title = $form['title']->getData();
            $content = $form['content']->getData();
            $now = new\DateTime('now');

            $entry->setTitle($title);
            $entry->setContent($content);

            $entry->setAuthor($user);
            $entry->setCreatedAt($now);

            $em = $this->getDoctrine()->getManager();

            $em->persist($entry);
            $em->flush();
            
            $this->addFlash('notice', 'Entry added');
            return $this->redirectToRoute('entry_list');
        }

        return $this->render('entry/create.html.twig', array(
            'form'=> $form->createView()
            ));
    }

    /**
     * @Route("/entry/edit/{id}", name="entry_edit")
     */
    public function editAction($id, Request $request)
    {
        $entry = $this->getDoctrine()
            ->getRepository('AppBundle:Entry')
            ->find($id);

        $entry->setTitle($entry->getTitle());
        $entry->setContent($entry->getContent());

        $entry->setAuthor($entry->getAuthor());

        $form = $this->createFormBuilder($entry)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                ->add('content', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
                ->add('author', HiddenType::class )
                ->add('save', SubmitType::class, array(
                                                'label' => 'Update Entry',
                                                'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                ->getForm();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $title = $form['title']->getData();
            $content = $form['content']->getData();
            $author = $this->getDoctrine()->getRepository('AppBundle:User')->find($form['author']->getData());
            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $entry = $em->getRepository('AppBundle:Entry')->find($id);

            $entry->setTitle($title);
            $entry->setContent($content);

            $entry->setAuthor($author);
            $entry->setUpdateAt($now);


            $em->flush();
            
            $this->addFlash('notice', 'Entry updated');
            return $this->redirectToRoute('entry_list');
        }

        return $this->render('entry/edit.html.twig', array(
                'entry' => $entry,
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/", name="entry_list")
     */
    public function listAction(Request $request)
    {
        /*$entries = $this->getDoctrine()
            ->getRepository('AppBundle:Entry')
            ->findBy(array(), array('createdAt' => 'DESC')); */

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM AppBundle:Entry a GROUP BY a.createdAt HAVING count(a.author) <= 3 ORDER BY a.createdAt DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate( $query,$request->query->getInt('page', 1), 3, array('wrap-queries'=>true));
        return $this->render('entry/index.html.twig', array('pagination' => $pagination));
    }
}