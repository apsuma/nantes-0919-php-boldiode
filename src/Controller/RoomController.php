<?php

namespace App\Controller;

use App\Model\FormCheck;
use App\Model\RoomManager;
use App\Model\ViewManager;
use App\Model\PriceManager;
use App\Model\ThemeManager;
use App\Model\PictureManager;

/**
 * Controller for the room interaction
 * Class RoomController
 */
class RoomController extends AbstractController
{
    public function show()
    {
        $roomManager = new RoomManager();
        $pictureManager = new PictureManager();
        $rooms = $roomManager->selectAllRooms();
        foreach ($rooms as $key => $room) {
            $rooms[$key]['images'] = $pictureManager->selectPicturesByRoom($room['id']);
        }
        return $this->twig->render("Room/show.html.twig", [
            'rooms' => $rooms,
        ]);
    }
}
