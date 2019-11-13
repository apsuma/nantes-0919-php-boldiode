<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\PriceManager;
use App\Model\RoomManager;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $roomManager = new RoomManager();
        $maxBed = $roomManager->maxBed();
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();
        return $this->twig->render('Home/index.html.twig', [
            'maxBed' => $maxBed,
            'prices' => $prices,
        ]);
    }
}
