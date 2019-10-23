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
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomManager = new RoomManager();
            $room = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'nb_bed' => $_POST['nb_bed'],
                'surface' => $_POST['surface'],
                'id_price' => $_POST['id_price'],
                'id_view' => $_POST['id_view'],
                'id_theme' => $_POST['id_theme'],
            ];
            $id = $roomManager->insert($room);
            header('Location:/room/show/' . $id);
        }

        return $this->twig->render('Room/add.html.twig');
    }
}
