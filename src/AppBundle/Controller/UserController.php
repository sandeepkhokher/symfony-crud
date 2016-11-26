<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()
                  ->getRepository('AppBundle:Users')
                  ->findAll();
        
        return $this->render('user/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'users' =>  $users,
        ]);
    }
    
    /**
     * @Route("/user/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new Users();
        
        $form = $this->createFormBuilder($user)
                  ->add('first_name', TextType::class,  array('attr'  =>  array('class' =>  'form-control')))
                  ->add('last_name', TextType::class,  array('attr'  =>  array('class' =>  'form-control')))
                  ->add('save', SubmitType::class,  array('label' =>  'Save', 'attr'  =>  array('class' =>  'btn btn-primary')))
                  ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
          $firstName = $form['first_name']->getData();
          $lastName = $form['last_name']->getData();
          
          $now  = new\DateTime('now');
          
          $user->setFirstName($firstName);
          $user->setLastName($lastName);
          $user->setCreatedOn($now);
          
          $em = $this->getDoctrine()->getManager();
          
          $em->persist($user);
          $em->flush();
          
          $this->addFlash(
                    'notice', 
                    'user added'
          );  
          
          return $this->redirectToRoute('user_list');
                  
        }
        
        return $this->render('user/create.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'  =>  $form->createView(),
        ]);
    }
    
    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editAction($id, Request $request)
    {
        $user = $this->getDoctrine()
                  ->getRepository('AppBundle:Users')
                  ->find($id);
        
        $form = $this->createFormBuilder($user)
                  ->add('first_name', TextType::class,  array('attr'  =>  array('class' =>  'form-control')))
                  ->add('last_name', TextType::class,  array('attr'  =>  array('class' =>  'form-control')))
                  ->add('save', SubmitType::class,  array('label' =>  'Save', 'attr'  =>  array('class' =>  'btn btn-primary')))
                  ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
          $firstName = $form['first_name']->getData();
          $lastName = $form['last_name']->getData();
          
          $now  = new\DateTime('now');
          
          $user->setFirstName($firstName);
          $user->setLastName($lastName);
          $user->setCreatedOn($now);
          
          $em = $this->getDoctrine()->getManager();
          
          $em->persist($user);
          $em->flush();
          
          $this->addFlash(
                    'notice', 
                    'user added'
          );  
          
          return $this->redirectToRoute('user_list');
                  
        }
        
        return $this->render('user/edit.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'user' =>  $user,
            'form'  =>  $form->createView(),
        ]);
    }
    
    /**
     * @Route("/user/details/{id}", name="user_details")
     */
    public function detailsAction($id)
    {
        $user = $this->getDoctrine()
                  ->getRepository('AppBundle:Users')
                  ->find($id);
        
        return $this->render('user/details.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'user' =>  $user,
        ]);
    }
    
    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')
                  ->find($id);
        $em->remove($user);
        $em->flush();
        
        $this->addFlash(
                    'notice', 
                    'user deleted'
        ); 
        return $this->redirectToRoute('user_list');
                  
    }
    
    
}
