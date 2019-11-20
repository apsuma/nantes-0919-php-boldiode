<?php

namespace App\Controller;

use App\Model\FormCheck;
use App\Model\ReservationSearchManager;
use App\Model\RoomManager;
use App\Model\ViewManager;
use App\Model\PriceManager;
use App\Model\ThemeManager;
use App\Model\PictureManager;
use DateTime;
use DateInterval;

/**
 * Controller for the room interaction
 * Class RoomController
 */
class RoomController extends AbstractController
{
    public function show(int $bed = 0, int $priceId = 0, string $tripStart = "", string $tripEnd = ""): ?string
    {
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();

        $date = new DateTime();
        $today = $date->format("Y-m-d");

        if ($tripStart == "" || $tripEnd == "") {
            $tripStart = $tripEnd = $today;
        }
        $tomorrow = $date->add(DateInterval::createFromDateString("1 day"))->format("Y-m-d");
        $maxDate = $date->add(DateInterval::createFromDateString("1 year"))->format("Y-m-d");

        $reservationSManager = new ReservationSearchManager();
        $reservationSManager->add($tripStart, $tripEnd);

        $roomManager = new RoomManager();
        $rooms = $roomManager->selectAllRooms($bed, $priceId);
        if (empty($rooms)) {
            header("Location:/room/emptysearch");
            return null;
        }

        $reservationSManager->clear();
        $rooms = $this->selectPicture($rooms);
        $maxBed = $roomManager->maxBed();
        return $this->twig->render("Room/show.html.twig", [
            'rooms' => $rooms,
            'prices' => $prices,
            'post' => $_POST,
            'maxBed' => $maxBed,
            'today' => $today,
            'maxDate' =>$maxDate,
            'tomorrow' => $tomorrow,
        ]);
    }

    public function search(): ?string
    {
        $roomManager = new RoomManager();

        if (isset($_POST['roomName'])) {
            $rooms = $roomManager->selectRoomByName($_POST['roomName']);
            if (empty($rooms)) {
                header("Location:/room/emptysearch");
                return null;
            }
            $rooms = $this->selectPicture($rooms);
            $maxBed = $roomManager->maxBed();
            $priceManager = new PriceManager();
            $prices = $priceManager->selectAll();
            $date = new DateTime();
            $today = $date->format("Y-m-d");
            $tomorrow = $date->add(DateInterval::createFromDateString("1 day"))->format("Y-m-d");
            $maxDate = $date->add(DateInterval::createFromDateString("1 year"))->format("Y-m-d");
            return $this->twig->render("Room/show.html.twig", [
                'rooms' => $rooms,
                'prices' => $prices,
                'post' => $_POST,
                'maxBed' => $maxBed,
                'today' => $today,
                'maxDate' =>$maxDate,
                'tomorrow' => $tomorrow,
            ]);
        } else {
            header("location: /room/show/" .
                $_POST['bed'] . "/" .
                $_POST['priceId'] . "/" .
                $_POST['tripStart'] . "/" .
                $_POST['tripEnd']);
            return null;
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

    public function emptySearch(): string
    {
        $roomManager = new RoomManager();
        $priceManager = new PriceManager();
        $maxBed = $roomManager->maxBed();
        $prices = $priceManager->selectAll();
        $date = new DateTime();
        $today = $date->format("Y-m-d");
        $tomorrow = $date->add(DateInterval::createFromDateString("1 day"))->format("Y-m-d");
        $maxDate = $date->add(DateInterval::createFromDateString("1 year"))->format("Y-m-d");

        return $this->twig->render("Room/empty.html.twig", [
            'maxBed' => $maxBed,
            'prices' => $prices,
            'today' => $today,
            'maxDate' => $maxDate,
            'tomorrow' => $tomorrow,
        ]);
    }
}
