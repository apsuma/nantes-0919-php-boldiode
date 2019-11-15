<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\PictureManager;
use App\Model\PriceManager;
use App\Model\RoomManager;
use DateInterval;
use DateTime;

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
    public function index(): string
    {
        $roomManager = new RoomManager();
        $priceManager = new PriceManager();
        $pictureManager = new PictureManager();
        $maxBed = $roomManager->maxBed();
        $prices = $priceManager->selectAll();
        $date = new DateTime();
        $today = $date->format("Y-m-d");
        $tomorrow = $date->add(DateInterval::createFromDateString("1 day"))->format("Y-m-d");
        $maxDate = $date->add(DateInterval::createFromDateString("1 year"))->format("Y-m-d");

        $rooms = $roomManager->selectRoomFavorite();
        foreach ($rooms as $key => $room) {
            $rooms[$key]['picture'] = $pictureManager->selectPicturesByRoom($room['roomId']);
        }

        return $this->twig->render('Home/index.html.twig', [
            'maxBed' => $maxBed,
            'prices' => $prices,
            'rooms' => $rooms,
            'today' => $today,
            'maxDate' => $maxDate,
            'tomorrow' => $tomorrow,
        ]);
    }
}
