<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\EnterNewPasswordType;
use App\Form\ForgotPasswordType;
use App\Form\PersonRegisterType;
use App\Repository\PersonRepository;
use Exception;
use Ramsey\Uuid\Uuid;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
    public function register(Request $request, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer, FlashBagInterface $bag)
    {
        $person = new Person();
        $form = $this->createForm(PersonRegisterType::class, $person);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = Uuid::uuid4()->toString();
            $person->setUuid($id);
            $person->setPassword($encoder->encodePassword($person, $person->getPlainPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            $msg = new Swift_Message('Confirmation Email');
            $msg->setFrom('alex@alex.test')
                ->setTo($person->getEmail())
                ->setBody($this->renderView('security/email/confirm.html.twig',
                    ['id' => $person->getId(), 'uuid' => $id]), 'text/html');
            $mailer->send($msg);
            $bag->add('success', 'A Confirmation Email Has Been Send!');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig',
            ['form' => $form->createView()]);

    }

    /**
     * @Route("/confirm/{id}/{uuid}", name="confirm")
     */
    public function confirm(Person $person, $uuid, FlashBagInterface $bag)
    {
        if ($person->getUuid() == $uuid) {
            $person->setConfirmed(true);
            $person->setUuid(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            $bag->add('success', 'You Have Been Confirmed!');
        } else if ($person->getConfirmed()) {
            $bag->add('warning', 'You Have Already Confirmed Your Account!');
        } else {
            $bag->add('danger', 'Your Account Could Not Be Confirmed!');
        }
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/forgot_password", name="forgot_password")
     * @throws Exception
     */
    public function forgotPassword(Request $request, PersonRepository $personRepository,
                                   Swift_Mailer $mailer, FlashBagInterface $bag)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $personRepository->findOneBy(['email' => $form->getData()['email']]);
            if ($person) {
                if ($person->getConfirmed()) {

                    $uuid = Uuid::uuid4()->toString();
                    $person->setUuid($uuid);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($person);
                    $em->flush();

                    $msg = new Swift_Message('Reset Your Password');
                    $msg->setFrom('alex@alex.test')
                        ->setTo($person->getEmail())
                        ->setBody($this->renderView('security/email/forgot_password.html.twig',
                            ['id' => $person->getId(), 'uuid' => $uuid]), 'text/html');
                    $mailer->send($msg);

                    $bag->set('success', 'An Email has been send');
                } else {
                    $bag->set('danger', 'Please Confirm Your Account First!');
                }
            } else {
                $bag->set('danger', 'No Matching Email Found!');
            }
        }
        return $this->render('security/forgot_password.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/forgot_password/{id}/{uuid}", name="forgot_password_data")
     * @throws Exception
     */
    public function forgotPasswordData(Request $request, PersonRepository $personRepository,
                                       FlashBagInterface $bag, $id, $uuid, UserPasswordEncoderInterface $encoder)
    {
        $person = $personRepository->find($id);
        if ($person && $person->getUuid() == $uuid) {
            $form = $this->createForm(EnterNewPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $person->setPassword($encoder->encodePassword($person, $form->getData()['password']));
                $person->setUuid(null);
                $em = $this->getDoctrine()->getManager();
                $em->persist($person);
                $em->flush();
                $bag->add('success', 'The Password Has Been Changed!');
                return $this->redirectToRoute('login');
            }
            return $this->render('security/forgot_password.html.twig',
                ['form' => $form->createView(), 'msg' => 'Set Your New Password']);
        }
        $bag->add('danger', 'The Data Is Invalid!');
        return $this->redirectToRoute('forgot_password');

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {
        $request->getSession()->invalidate();

    }
}
