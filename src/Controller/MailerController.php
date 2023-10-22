<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
// use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class MailerController extends AbstractController
{
    #[Route('/admin/reset-password/{userId}', name: 'admin_reset_password')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('no-reply@serenity-estate.com')
            ->to('agent1@serenity-estate.com')
            ->subject('Link to test symfony mailer ')
            ->text('Your password reset request!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        // return new Response(
        //     'Email was sent'
        // );

        return $this->render(('/mailer/index.html.twig'));
    }

    //     public function adminResetPassword(Request $request, $userId, ResetPasswordHelperInterface $resetPasswordHelper)
    //     {
    //         $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    //         if (!$user) {
    //             // Handle the case where the user with the given ID doesn't exist.
    //             // You can return a response or redirect as needed.
    //         }

    //         // Generate a password reset token for the user
    //         $resetToken = $resetPasswordHelper->generateResetToken($user);

    //         // Send the password reset email
    //         $this->sendPasswordResetEmail($user, $resetToken);
    //     }
    //     private function sendPasswordResetEmail(User $user, $token)
    //     {
    //     }
}
