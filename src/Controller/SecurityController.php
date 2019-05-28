<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonRegisterType;
use App\Form\PersonType;
use Exception;
use Ramsey\Uuid\Uuid;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        return $this->render('security/login.html.twig',
            ['error' => $error, 'last_email' => $utils->getLastUsername()]);

    }

    /**
     * @Route("/register", name="register")
     * @throws Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer)
    {
        $person = new Person();
        $form = $this->createForm(PersonRegisterType::class, $person);
        $id = Uuid::uuid4()->toString();
        $person->setConfirmationId($id);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person->setPassword($encoder->encodePassword($person, $person->getPlainPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            $msg = new Swift_Message('Confirmation Email');
            $msg->setFrom('konzertverwaltung@tms-badoldesloe.de')
                ->setTo($person->getEmail())
                ->setBody("<a href=\"http://localhost:8000/confirm/" .
                    $person->getId() . "/" . $person->getConfirmationId() . "\" >CONFIRM EMAIL </a>", 'text/html');
            $mailer->send($msg);
            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig',
            ['form' => $form->createView()]);

    }

    /**
     * @Route("/confirm/{id}/{uuid}/", name="confirm")
     */
    public function confirm(Person $person, $uuid)
    {
        if ($person->getConfirmationId() == $uuid) {
            $person->setConfirmed(true);
            $person->setConfirmationId(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return new Response("YOU HAVE BEEN VERIFIED");
        } else if ($person->getConfirmed()) {
            return new Response("YOU HAVE ALREADY CONFIRMED YOUR ACCOUT");
        }
        return new Response("THE ID IS INVALID :-(");

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {
        $request->getSession()->invalidate();

    }
}
