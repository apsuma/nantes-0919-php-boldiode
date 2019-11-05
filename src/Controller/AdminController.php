<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function logIn()
    {
        return $this->twig->render("Admin/logIn.html.twig");
    }
}
