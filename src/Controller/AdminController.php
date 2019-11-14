<?php

namespace App\Controller;

use App\Model\AdminManager;
use App\Model\FormCheck;
use App\Model\PictureManager;
use App\Model\PriceManager;
use App\Model\ReservationManager;
use App\Model\RoomManager;
use App\Model\ThemeManager;
use App\Model\ViewManager;
use DateInterval;
use DateTime;

class AdminController extends AbstractController
{
    public function logIn(): ?string
    {
        if (isset($_SESSION['admin'])) {
            header("Location:/admin/editlist/?message=Vous êtes déjà connecté");
            return null;
        }
        return $this->twig->render("Admin/logIn.html.twig");
    }

    public function addAdmin(): ?string
    {
        if ($_SESSION['admin'] == 'admin') {
            $this->checkAdmin();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $adminManager = new AdminManager();
                $result = $adminManager->add($_POST['login'], $_POST['pwd']);
                header("Location: /admin/editlist/?message=cet administrateur $result");
                return null;
            }
            return $this->twig->render("Admin/addAdmin.html.twig");
        }
        header("Location:/admin/editlist/?message=Vous ne pouvez pas ajouter de nouvel administrateur");
    }

    public function log(): void
    {
        $adminManager = new AdminManager();
        $admin = $adminManager->selectByName($_POST['login']);
        if (!empty($admin)) {
            if (password_verify($_POST['pwd'], $admin['pwd'])) {
                $_SESSION['admin'] = $admin['login'];
                header("Location:/admin/editlist/?message=Vous êtes bien connecté");
            }
        }
        header("location:/admin/login");
    }

    public function checkAdmin(): void
    {
        if (!isset($_SESSION['admin'])) {
            header("location:/");
        }
    }

    public function logOut(): void
    {
        session_destroy();
        header("location:/admin/login");
    }

    public function editList(): string
    {
        $this->checkAdmin();
        $roomEdit = new RoomManager();
        $roomList = $roomEdit->selectAllOrderByFront();
        return $this->twig->render('Admin/editList.html.twig', ['roomList' => $roomList]);
    }

    public function edit(int $id): ?string
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

        $nameError = $descriptionError = $nbBedError = $surfaceError = null;
        $idPriceError = $idViewError = $idThemeError = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formUpdateCheck = new FormCheck($_POST);
            $nameError = $formUpdateCheck->shortText('name');
            $descriptionError = $formUpdateCheck->text('description');
            $nbBedError = $formUpdateCheck->number('nb_bed');
            $surfaceError = $formUpdateCheck->number('surface');
            $idPriceError = $formUpdateCheck->number('id_price');
            $idViewError = $formUpdateCheck->number('id_view');
            $idThemeError = $formUpdateCheck->number('id_theme');

            if ($formUpdateCheck->getValid()) {
                $roomEdit->updateRoom($_POST);
                $pictureManager->updatePicturesByRoom($_POST);
                if (isset($_POST['image']) && !empty($_POST['image'])) {
                    $picture = ['image' => $_POST['image'], 'description' => ""];
                    $pictureManager->insert($picture, $_POST['id']);
                }
                header('Location:/admin/edit/' . $_POST['id'] . '/?message=la chambre a bien été modifiée');
                return null;
            }
        }
        return $this->twig->render('Admin/edit.html.twig', [
            'room' => $room,
            'views' => $views,
            'prices' => $prices,
            'themes' => $themes,
            'pictures' => $pictures,
            'nameError' => $nameError,
            'descriptionError' => $descriptionError,
            'nbBedError' => $nbBedError,
            'surfaceError' => $surfaceError,
            'idPriceError' => $idPriceError,
            'idViewError' => $idViewError,
            'idThemeError' => $idThemeError,
        ]);
    }

    public function add(): ?string
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
                return null;
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
            'post' => $_POST,
        ]);
    }

    public function delete(int $id): void
    {
        $this->checkAdmin();
        $roomManager = new RoomManager();
        $pictureManager = new PictureManager();
        $pictureManager->deleteRoomId($id);
        $roomManager->delete($id);
        header("Location:/admin/editList/?message=une chambre a bien été supprimée");
    }

    public function editFrontPage(int $id, $state = null)
    {
        $this->checkAdmin();
        $roomManager = new RoomManager();
        $roomManager->updateFrontPage($id, $state);
    }

    public function planning(int $idRoom): string
    {
        $this->checkAdmin();
        $reservationManager = new ReservationManager();

        $customers = $reservationManager->selectRoom($idRoom);

        $date = new DateTime();
        $today = $date->format("Y-m-d");
        $maxDate = $date->add(DateInterval::createFromDateString("1 year"))->format("Y-m-d");

        return $this->twig->render("Admin/planning.html.twig", [
            "customers" => $customers,
            "today" => $today,
            "maxDate" => $maxDate,
            "idRoom" => $idRoom,
            ]);
    }

    public function planningDelete(int $idRoom, string $date): ?string
    {
        $this->checkAdmin();
        $reservationManager = new ReservationManager();
        $reservationManager->deleteDate($idRoom, $date);
        header("Location:/admin/planning/$idRoom");
        return null;
    }

    public function planningAdd(int $idRoom)
    {
        $this->checkAdmin();

        $dateStart = date_create_from_format("Y-m-d", $_POST['tripStart']);
        $dateEnd = date_create_from_format("Y-m-d", $_POST['tripEnd']);
        $dateDiff = date_diff($dateStart, $dateEnd);
        $oneDay = new DateInterval("P1D");
        $dates[1] = $dateStart->format("Y-m-d");
        for ($i = $dateDiff->d; $i > 1; $i--) {
            $dates[$i] = $dateStart->add($oneDay)->format("Y-m-d");
        }

        $reservationManager = new ReservationManager();
        foreach ($dates as $date) {
            $reservationManager->add($idRoom, $_POST['name'], $date);
        }

        header("Location:/admin/planning/$idRoom");
        return null;
    }
}
