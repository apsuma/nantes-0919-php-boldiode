<?php

namespace App\Controller;

class HistoryController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('History/index.html.twig');
    }
}
