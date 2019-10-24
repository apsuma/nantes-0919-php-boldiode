<?php
/**
 * Controller for the room interaction
 */

namespace App\Controller;

use App\Model\RoomManager;
use App\Model\ViewManager;
use App\Model\PriceManager;
use App\Model\ThemeManager;
use App\Model\PictureManager;

/**
 * Class RoomController
 * @package App\Controller
 */
class RoomController extends AbstractController
{

    public function edit(int $id = null): string
    {
        $roomEdit = new RoomManager();
        if ($id) {
            $room = $roomEdit->selectRoomById($id);
            $viewManager = new ViewManager();
            $views = $viewManager->selectAll();
            $priceManager = new PriceManager();
            $prices = $priceManager->selectAll();
            $themeManager = new ThemeManager();
            $themes = $themeManager->selectAll();
            return $this->twig->render('Room/edit.html.twig', [
                'room' => $room,
                'views' => $views,
                'prices' => $prices,
                'themes' => $themes,
            ]);
        } else {
            $roomList = $roomEdit->selectAll();
            return $this->twig->render('Room/editList.html.twig', ['roomList' => $roomList]);
        }
    }

    public function add()
    {
        $viewManager = new ViewManager();
        $views = $viewManager->selectAll();
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();
        $themeManager = new ThemeManager();
        $themes = $themeManager->selectAll();
        $pictureManager = new PictureManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomManager = new RoomManager();
            $id = $roomManager->insert($_POST);
            $pictureManager->insert($_POST, $id);
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
        return $this->twig->render("Room/show.html.twig", ['rooms' => $rooms]);
    }
}
