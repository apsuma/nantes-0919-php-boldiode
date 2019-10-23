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
    public function edit(int $id): string
    {
        $roomEdit = new RoomManager();
        $room = $roomEdit->selectOneById($id);
        return $this->twig->render('Room/edit.html.twig', ['room' => $room]);
    }
}
