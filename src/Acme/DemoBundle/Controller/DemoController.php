<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// these import the "@Cache" annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DemoController extends Controller
{
    /**
     * @Route("/", name="_demo")
     * @Template()
     * @Cache(maxage="86400")
     */
    public function indexAction()
    {
        //return array();
        //throw $this->createNotFoundException(); 
        //throw new \Exception("Error Processing Request");
        
        //$response = $this->render('AcmeDemoBundle:Demo:index.txt.twig');
        //$response->headers->set('Content-Type', 'text/plain');
        //return $response;    
       return  $this->render('AcmeDemoBundle:Demo:hello.html.twig', array(
            'name' => 'asdad',
        ));

    }
    
    /**
     * @Route("/top-articles/{num}", name="_demo_top")
     * @Template()
     */
     public function topArticlesAction($num = 0)
     { 
        // look most popular articles in the database
        $articles = "";
        return $this->render('AcmeDemoBundle:Demo:topArticles.html.twig', array(
           'articles' => $articles 
        ));

     } 

    /**
     * @Route( "/hello/{name}.{_format}",
     *         defaults = { "_format" = "html" },
     *         requirements = { "_format" = "html|xml|json" },
     *         name = "_demo_hello" 
     * )
     * @Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {

        $request->isXmlHttpRequest(); // is it an Ajax request?
        $request->getPreferredLanguage(array('en', 'fr'));
        $request->query->get('page');   // get a $_GET parameter
        $request->request->get('page'); // get a $_POST parameter



        $session = $request->getSession();
        // store an attribute for reuse during a later user request
        $session->set('foo', 'bar');
        // get the value of a session attribute
        $foo = $session->get('foo');
        // use a default value if the attribute doesn't exist
        $foo = $session->get('foo', 'default_value');
        // store a message for the very next request (in a controller)
        $session->getFlashBag()->add('notice', 'Congratulations, your action succeeded!');




        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailer = $this->get('mailer');

            // .. setup a message and send it
            // http://symfony.com/doc/current/cookbook/email.html

            $request->getSession()->getFlashBag()->set('notice', 'Message sent!');

            return new RedirectResponse($this->generateUrl('_demo'));
        }

        return array('form' => $form->createView());
    }
}
