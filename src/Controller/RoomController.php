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
    public function show($bed = 0, $priceId = 0) : string
    {
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();
        $roomManager = new RoomManager();
        $rooms = $roomManager->selectAllRooms($bed, $priceId);
        $rooms = $this->selectPicture($rooms);
        $maxBed = $roomManager->maxBed();
        return $this->twig->render("Room/show.html.twig", [
            'rooms' => $rooms,
            'prices' => $prices,
            'post' => $_POST,
            'maxBed' => $maxBed,
        ]);
    }

    public function search()
    {
        $roomManager = new RoomManager();
        $_POST['roomId'] = 0;
        if ($_POST['roomId'] != 0) {
            $room = $roomManager ->selectRoomById($_POST['roomId']);
            $room = $this->selectPicture($room);
            return $this->twig->render("Room/showOneRoom.html.twig", [
                'room' => $room,
            ]);
        } else {
            header("location: /room/show/" . $_POST['bed'] . "/" . $_POST['priceId']);
        }
    }

    /**
     * return an array with the pictures of each room linked to their rooms
     * @param array $rooms
     * @return array
     */
    public function selectPicture(array $rooms)
    {
        $pictureManager = new PictureManager();
        if (isset($rooms[0])) {
            foreach ($rooms as $key => $room) {
                $rooms[$key]['images'] = $pictureManager->selectPicturesByRoom($room['roomId']);
            }
        } else {
            $rooms['images'] = $pictureManager->selectPicturesByRoom($rooms['roomId']);
        }
        return $rooms;
    }
}
