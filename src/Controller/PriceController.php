<?php


namespace App\Controller;

use App\Model\AdminManager;
use App\Model\FormCheck;
use App\Model\PriceManager;

class PriceController extends AbstractController
{
    public function editListPrice(): string
    {
        return $this->twig->render('Price/editListPrice.html.twig');
    }
}
