<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\DoctrineHelper;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route('/login_check', name: 'login_check')]
    public function check(): never
    {
        throw new \LogicException('This code should never be reached');
    }

    #[Route('/login', name: 'login')]
    public function requestLoginLink(AuthenticationUtils $authenticationUtils, NotifierInterface $notifier, LoginLinkHandlerInterface $loginLinkHandler, ManagerRegistry $registry, TranslatorInterface $translator, UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, ['label' => 'login.form.email', 'translation_domain' => 'security'])
            ->add('submit', SubmitType::class, ['label' => 'login.form.submit', 'translation_domain' => 'security'])
            ->getForm();

        if ($authenticationUtils->getLastAuthenticationError()) {
            $message = $translator->trans('login.invalid_link', [], 'security');
            $this->addFlash('danger', $message);
        }

        $form->handleRequest($request);
        $linkSent = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $user = $userRepository->findOneBy(['email' => $email]);

            $isAdmin = $user && $user->isAdmin();
            if (!$isAdmin && 0 === $userRepository->count()) {
                $user = new User();
                $user->setEmail($email);
                $user->setIsAdmin(true);

                DoctrineHelper::persistAndFlush($registry, $user);
                $isAdmin = true;
            }

            if (!$isAdmin) {
                $this->addFlash('danger', $translator->trans('login.danger.not_admin', [], 'security'));
            } else {
                $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

                $subject = $translator->trans('login.notification.subject', [], 'security');
                $notification = new LoginLinkNotification($loginLinkDetails, $subject);
                $recipient = new Recipient($user->getEmail());
                $notifier->send($notification, $recipient);

                $linkSent = true;
            }
        }

        return $this->render('security/login.html.twig', ['form' => $form->createView(), 'success' => $linkSent]);
    }
}
