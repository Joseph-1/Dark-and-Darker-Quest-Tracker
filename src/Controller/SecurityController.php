<?php

namespace App\Controller;

use App\Form\ResetPasswordFormTypeForm;
use App\Form\ResetPasswordRequestFormTypeForm;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/forgotten-password', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        JWTService $jwt,
        SendEmailService $mail
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormTypeForm::class );

        // Retrieve content of post to manipulate it
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Form is send and valid
            $user = $userRepository->findOneByEmail($form->get('email')->getData()); // Fetch user in database

            // Check if there is a user
            if($user) {
                // We have a user
                // Generate a JWT
                // Header
                $header = [
                    'alg' => 'HS256',
                    'typ' => 'JWT',
                ];

                // Payload
                $payload = [
                    'user_id' => $user->getId(),
                ];

                // Generate Token
                $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                // Generate URL to reset_password
                // Need the URL Generator Interface
                $url = $this->generateUrl('reset_password', ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL); // When it's in an email we need an absolute URL

                // Send email
                $mail->send(
                    'no-reply@questtracker.com',
                    $user->getEmail(),
                    'Retrieve your password - Quest Tracker',
                    'password_reset',
                    compact('user', 'url') // = ['user' => $user, 'url' => $url]
                );

                $this->addFlash('success', 'Email sent with success.');
                return $this->redirectToRoute('app_login');


            }

            // $user is NULL
            $this->addFlash('danger', 'A problem has been encountered, please try again.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/forgotten-password/{token}', name: 'reset_password')]
    public function resetPassword(
        $token,
        JWTService $jwt,
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
    ): Response
    {
        // Check if token is valid (coherent, not expired and signature valid)
        if ($jwt->isValid($token) && !$jwt->isExpired($token) &&
            $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            // Token is valid
            // Retrieve data (Payload)
            $payload = $jwt->getPayload($token);

            // Retrieve user
            $user = $userRepository->find($payload['user_id']);

            if ($user) {
                $form  = $this->createForm(ResetPasswordFormTypeForm::class );

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword(
                        $passwordHasher->hashPassword($user, $form->get('password')->getData())
                    );

                    $em->flush();

                    $this->addFlash('success', 'Password changed successfully.');
                    return $this->redirectToRoute('app_login');
                }
                return $this->render('security/reset_password.html.twig', [
                    'passForm' => $form->createView(),
                ]);
            }
        }
        $this->addFlash('danger', 'Token is invalid or expired.');
        return $this->redirectToRoute('app_login');
    }
}
