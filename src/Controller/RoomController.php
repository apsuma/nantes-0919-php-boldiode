<?php
/**
 * Controller for the room interaction
 */

namespace App\Controller;

use App\Model\RoomManager;

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
            return $this->twig->render('Room/edit.html.twig', ['room' => $room]);
        } else {
            $roomList = $roomEdit->selectAll();
            return $this->twig->render('Room/editList.html.twig', ['roomList' => $roomList]);
        }
    }
}
