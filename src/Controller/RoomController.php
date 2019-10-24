<?php
/**
 * Controller for the room interaction
 */

namespace App\Controller;

use App\Model\RoomManager;
use App\Model\ViewManager;
use App\Model\PriceManager;
use App\Model\ThemeManager;

/**
 * Class RoomController
 * @package App\Controller
 */
class RoomController extends AbstractController
{
    public function add()
    {
        $viewManager = new ViewManager();
        $views = $viewManager->selectAll();
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();
        $themeManager = new ThemeManager();
        $themes = $themeManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomManager = new RoomManager();
            $roomManager->insert($_POST);
            header('Location:/room/show');
        }

        return $this->twig->render('Room/add.html.twig', [
            'views' => $views,
            'prices' => $prices,
            'themes' => $themes
        ]);
    }

    public function show()
    {
        $roomManager = new RoomManager();
        $rooms = $roomManager->selectAllRooms();
        return $this->twig->render("Room/show.html.twig", ['rooms'=>$rooms]);
    }
}
