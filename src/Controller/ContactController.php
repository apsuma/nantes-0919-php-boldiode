<?php


namespace App\Controller;

use App\Model\FormCheck;

class ContactController extends AbstractController
{

    public function sendMail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactNameError = $contactEmailError = $contentError = $subjectError = $phoneError= null;

            $formCheck = new FormCheck($_POST);
            $contactNameError = $formCheck->shortText('contactName');
            $contactEmailError = $formCheck->email('contactEmail');
            $contentError = $formCheck->text('content');
            $subjectError = $formCheck->shortText('subject');
            $phoneError = $formCheck->number('phone');

            if ($formCheck->getValid()) {
                $sentence = $_POST['contactName'] . '-from: ' . $_POST['contactEmail'];
                $sentence = $sentence . 'message :' . $_POST['content'];
                $sentence = $sentence . 'tel :' . $_POST['phone'];
                print_r($sentence);
                header("Location:/");
            }
        }
        return $this->twig->render('Home/contact.html.twig', ['contactForm' => $_POST]);
    }
}
