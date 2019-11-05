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

    public function edit(int $id): string
    {
        $roomEdit = new RoomManager();
        $room = $roomEdit->selectRoomById($id);
        $viewManager = new ViewManager();
        $views = $viewManager->selectAll();
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();
        $themeManager = new ThemeManager();
        $themes = $themeManager->selectAll();
        $pictureManager = new PictureManager();
        $pictures = $pictureManager->selectPicturesByRoom($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomEdit->updateRoom($_POST);
            $pictureManager->updatePicturesByRoom($_POST);
            if (isset($_POST['image']) && !empty($_POST['image'])) {
                $picture = ['image' => $_POST['image'], 'description' => ""];
                $pictureManager->insert($picture, $_POST['id']);
            }
            header('Location:/room/edit/' . $_POST['id']);
        }
        return $this->twig->render('Room/edit.html.twig', [
            'room' => $room,
            'views' => $views,
            'prices' => $prices,
            'themes' => $themes,
            'pictures' => $pictures,
        ]);
    }

    public function editList()
    {
        $roomEdit = new RoomManager();
        $roomList = $roomEdit->selectAll();
        return $this->twig->render('Room/editList.html.twig', ['roomList' => $roomList]);
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

        $nameError = $descriptionError = $nbBedError = $surfaceError = null;
        $idPriceError = $idViewError = $idThemeError = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formCheck = new FormCheck($_POST);
            $nameError = $formCheck->shortText('name');
            $descriptionError = $formCheck->text('description');
            $nbBedError = $formCheck->number('nb_bed');
            $surfaceError = $formCheck->number('surface');
            $idPriceError = $formCheck->number('id_price');
            $idViewError = $formCheck->number('id_view');
            $idThemeError = $formCheck->number('id_theme');

            if ($formCheck->getValid()) {
                $roomManager = new RoomManager();
                $id = $roomManager->insert($_POST);
                $pictureManager->insert($_POST, $id);
                header('Location:/room/show');
            }
        }

        return $this->twig->render('Room/add.html.twig', [
            'views' => $views,
            'prices' => $prices,
            'themes' => $themes,
            'nameError' => $nameError,
            'descriptionError' => $descriptionError,
            'nbBedError' => $nbBedError,
            'surfaceError' => $surfaceError,
            'idPriceError' => $idPriceError,
            'idViewError' => $idViewError,
            'idThemeError' => $idThemeError,
            'post' =>$_POST,
        ]);
    }

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

    public function delete(int $id)
    {
        $roomManager = new RoomManager();
        $pictureManager = new PictureManager();
        $pictureManager->deleteRoomId($id);
        $roomManager->delete($id);
        header("Location:/room/editList");
    }
}
