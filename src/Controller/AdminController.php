<?php

namespace App\Controller;

use App\Model\AdminManager;
use App\Model\FormCheck;
use App\Model\PictureManager;
use App\Model\PriceManager;
use App\Model\RoomManager;
use App\Model\ThemeManager;
use App\Model\ViewManager;

class AdminController extends AbstractController
{
    public function logIn()
    {
        return $this->twig->render("Admin/logIn.html.twig");
    }

    public function log()
    {
        $adminManager = new AdminManager();
        $admins = $adminManager->selectAll();
        foreach ($admins as $admin) {
            if ($_POST['login'] === $admin['login'] && $_POST['pwd'] === $admin['pwd']) {
                $_SESSION['admin'] = $admin['login'];
                header("Location:/admin/editlist/?message=Vous êtes bien connecté");
            } else {
                header("location:/admin/login");
            }
        }
    }

    public function checkAdmin()
    {
        if (!isset($_SESSION['admin'])) {
            header("location:/");
        }
    }

    public function logOut()
    {
        session_destroy();
        header("location:/admin/login");
    }

    public function editList()
    {
        $this->checkAdmin();
        $roomEdit = new RoomManager();
        $roomList = $roomEdit->selectAll();
        return $this->twig->render('Admin/editList.html.twig', ['roomList' => $roomList]);
    }

    public function edit(int $id): string
    {
        $this->checkAdmin();
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
            header('Location:/admin/edit/' . $_POST['id'] . '/?message=la chambre a bien été modifiée');
        }
        return $this->twig->render('Admin/edit.html.twig', [
            'room' => $room,
            'views' => $views,
            'prices' => $prices,
            'themes' => $themes,
            'pictures' => $pictures,
        ]);
    }

    public function add()
    {
        $this->checkAdmin();
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
                header('Location:/admin/editList/?message=une chambre a bien été ajoutée');
            }
        }

        return $this->twig->render('Admin/add.html.twig', [
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

    public function delete(int $id)
    {
        $this->checkAdmin();
        $roomManager = new RoomManager();
        $pictureManager = new PictureManager();
        $pictureManager->deleteRoomId($id);
        $roomManager->delete($id);
        header("Location:/admin/editList/?message=une chambre a bien été supprimée");
    }
}