<?php

namespace App\Controller;

use App\Model\FormCheck;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    public function sendMail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactNameError = $contactEmailError = $contentError = $subjectError = $phoneError= null;

            $formCheck = new FormCheck($_POST);
            $contactNameError = $formCheck->shortText('contactName');
            $contactEmailError = $formCheck->email('contactEmail');
            $contentError = $formCheck->shortText('content');
            $subjectError = $formCheck->shortText('subject');
            $phoneError = $formCheck->phoneNumber('phone');

            if ($formCheck->getValid()) {
                $sentence = $_POST['contactName'] . ' -from: ' . $_POST['contactEmail'].' - ';
                $sentence = $sentence . 'message :' . $_POST['content'];
                $sentence = $sentence . ' - tel pour rappeler : ' . $_POST['phone'];
                $email = (new Email())
                    ->from($_POST['contactEmail'])
                    ->to(CONTACT_EMAIL_RECIPIENT)
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject($_POST['subject'])
                    // If you want use text mail only
                    ->text($sentence)
                    // Raw html
                    ->html($sentence)
                ;
                $transport = new GmailTransport(GMAIL_USER, GMAIL_PWD);
                $mailer = new Mailer($transport);
                $mailer->send($email);
                header('Location:/Home/index/?message=Votre message a bien été envoyé à boldiode@gmail.com');
            }
            return $this->twig->render('Home/contact.html.twig', [
                'contactForm' => $_POST,
                'contactNameError' => $contactNameError,
                'contactEmailError' => $contactEmailError,
                'contentError' => $contentError,
                'subjectError' => $subjectError,
                'phoneError' => $phoneError,
            ]);
        }
        return $this->twig->render('Home/contact.html.twig', [
            'contactForm' => $_POST,
        ]);
    }
}
