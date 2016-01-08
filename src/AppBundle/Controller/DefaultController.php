<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ContactType;

class DefaultController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     * @Method({"GET", "POST"})
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $datas = $form->getData();
            $body = $this->renderView('email/mailform.text.twig', [
                'datas' => $datas,
            ]);

            $message = \Swift_Message::newInstance()
                ->setSubject($datas['subject'])
                ->setFrom([$datas['email'] => $datas['name']])
                ->setTo($this->getParameter('mailform_to'))
                ->setReplyTo([$datas['email'] => $datas['name']])
                ->setBody($body);
            $this->get('mailer')->send($message);

            $this->addFlash('success', 'お問い合わせ内容をメールで送信しました。');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/{page}", name="pages", requirements={ "page": "^[^_].+" })
     * @Method("GET")
     */
    public function pageAction(Request $request, $page)
    {
        if (! $this->get('templating')->exists("pages/{$page}.html.twig")) {
            throw $this->createNotFoundException("Page Not Found");
        }
        return $this->render("pages/{$page}.html.twig");
    }
}
