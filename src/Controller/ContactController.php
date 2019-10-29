<?php


namespace App\Controller;

class ContactController extends AbstractController
{

    public function sendMail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactform = [
                'contactName' => $_POST['contactName'],
                'contactEmail' => $_POST['contactEmail'],
                'content' => $_POST['content'],
                'subject' => $_POST['subject'],
                'phone' => $_POST['phone'],
            ];

            $sentence = $contactform['contactName'].'-from: '.$contactform['contactEmail'];
            $sentence = $sentence. 'message :'. $contactform['content'];
            $toBoldIode = 'boldiode@gmail.com';
            $subject = 'blog boldiode -'.$contactform['subject'];
            mail($toBoldIode, $subject, $sentence);
        }
        return $this->twig->render('/Home/contact.html.twig');
    }
}
