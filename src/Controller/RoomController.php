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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomEdit->updateRoom($_POST);
            header('Location:/room/edit/' . $_POST['id']);
        }
        return $this->twig->render('Room/edit.html.twig', [
            'room' => $room,
            'views' => $views,
            'prices' => $prices,
            'themes' => $themes,
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
        $nameError = $descriptionError = $nbBedError = $surfaceError =
        $idPriceError = $idViewError = $idThemeError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formCheck = new FormCheck($_POST);
            $nameError = $formCheck->shortText('name');
            $descriptionError = $formCheck->text('description');
            $nbBedError = $formCheck->number('nb_bed');
            $surfaceError = $formCheck->number('surface');
            $idPriceError = $formCheck->shortText('id_price');
            $idViewError = $formCheck->shortText('id_view');
            $idThemeError = $formCheck->shortText('id_theme');

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
