<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        JWTService $jwt,
        SendEmailService $mail
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // set ROLE_USER to any register member
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // Generate Token
            // Header
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT',
            ];

            // Payload => can't do this without persist user line:  30
            $payload = [
                'user_id' => $user->getId(),
            ];

            // Generate Token
            // $this->getParameter('app.jwtsecret') = go in services.yaml and with the parameters go in field .env
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Send email
            $mail->send(
                'no-reply@questtracker.com',
                $user->getEmail(),
                'Account activation for Dark and Darker - Quest Tracker',
                'register',
                compact('user', 'token') // = ['user' => $user, 'token' => $token]
            );

            $this->addFlash('success', 'You are register, please check your email to activate
            your account.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    // Allow to activate account if user click on the link
    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository,
                               EntityManagerInterface $em): Response
    {
        // Check if token is valid (coherent, not expired and signature valid)
        if ($jwt->isValid($token) && !$jwt->isExpired($token) &&
            $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            // Token is valid
            // Retrieve data (Payload)
            $payload = $jwt->getPayload($token);

            // Retrieve user
            $user = $userRepository->find($payload['user_id']);

            // Check if there is a user and that isn't already activate
            if ($user && !$user->isVerified()) {
                $user->setIsVerified(true);
                $em->flush();

                $this->addFlash('success', 'Your account has been verified.');

                return $this->redirectToRoute('app_home');
            }
        }
        $this->addFlash('danger', 'Token is invalid or expired.');

        return $this->redirectToRoute('app_home');
    }
}
