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
    public function show($bed = 0, $priceId = 0): string
    {
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();

        $roomManager = new RoomManager();
        $rooms = $roomManager->selectAllRooms($bed, $priceId);
        if (empty($rooms)) {
            $rooms = $roomManager->selectAllRooms();
        }
        $rooms = $this->selectPicture($rooms);

        $maxBed = $roomManager->maxBed();
        return $this->twig->render("Room/show.html.twig", [
            'rooms' => $rooms,
            'prices' => $prices,
            'post' => $_POST,
            'maxBed' => $maxBed,
        ]);
    }

    public function search(): string
    {
        $roomManager = new RoomManager();

        if (isset($_POST['roomName'])) {
            $rooms = $roomManager->selectRoomByName($_POST['roomName']);
            if (empty($rooms)) {
                $rooms = $roomManager->selectAllRooms();
            }
            $rooms = $this->selectPicture($rooms);
            $maxBed = $roomManager->maxBed();
            $priceManager = new PriceManager();
            $prices = $priceManager->selectAll();
            return $this->twig->render("Room/show.html.twig", [
                'rooms' => $rooms,
                'prices' => $prices,
                'post' => $_POST,
                'maxBed' => $maxBed,
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
    public function selectPicture(array $rooms): array
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
