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
use App\Service\ImageUploader;
use DateInterval;
use DateTime;
use mysql_xdevapi\Exception;

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

    public function editList($front = null): string
    {
        $this->checkAdmin();
        $roomEdit = new AdminManager();
        $roomList = $roomEdit->selectAllOrderByNameFront($front);
        $front = $front == 'front' ? 'front' : '';
        return $this->twig->render('Admin/editList.html.twig', ['roomList' => $roomList,
            'front' => $front]);
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
        $imageUploader = new ImageUploader();

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
                $pictureCount = count($_FILES['image']['name']);

                for ($i=0; $i < $pictureCount; $i++) {
                    $formUpdateCheck->image($_FILES, $i);
                    if ($formUpdateCheck->getValid()) {
                        $fileTmpName = $_FILES['image']['tmp_name'][$i];
                        $fileName = $_FILES['image']['name'][$i];
                        $fileExplode = explode('.', $fileName);
                        $fileActualExt = strtolower(end($fileExplode));
                        $filename = $imageUploader->uploadImage($fileTmpName, $fileActualExt);
                        $pictureManager->insert($_POST, $id, $filename);
                    }
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
        $imageUploader = new ImageUploader();
        $roomManager = new RoomManager();

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
                $id = $roomManager->insert($_POST);
                $pictureCount = count($_FILES['image']['name']);
                for ($i=0; $i < $pictureCount; $i++) {
                    $formCheck->image($_FILES, $i);
                    if ($formCheck->getValid()) {
                        $fileTmpName = $_FILES['image']['tmp_name'][$i];
                        $fileName = $_FILES['image']['name'][$i];
                        $fileExplode = explode('.', $fileName);
                        $fileActualExt = strtolower(end($fileExplode));
                        $filename = $imageUploader->uploadImage($fileTmpName, $fileActualExt);
                        $pictureManager->insert($_POST, $id, $filename);
                    }
                }
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
        $reservationManager = new ReservationManager();
        if (count($reservationManager->selectRoom($id)) > 0) {
            header('Location:/admin/editList/?message=Chambre réservée, impossible à supprimer');
            return;
        }
        $roomManager = new RoomManager();
        $pictureManager = new PictureManager();
        $imageDeleter = new ImageUploader();
        $picturesInRoom = $pictureManager->selectPicturesByRoom($id);
        foreach ($picturesInRoom as $picture) {
            $imageDeleter->delete($picture['image']);
        }
        $pictureManager->deleteRoomId($id);
        $roomManager->delete($id);
        header("Location:/Admin/editList/?message=une chambre a bien été supprimée");
    }

    public function editFrontPage(int $id, int $state, ?string $front = null): void
    {
        $this->checkAdmin();
        $roomManager = new RoomManager();
        $roomManager->updateFrontPage($id, $state, $front);
    }

    public function planning(int $idRoom): string
    {
        $this->checkAdmin();
        $reservationManager = new ReservationManager();
        $roomManager = new RoomManager();
        $room = $roomManager->selectOneById($idRoom);

        $customers = $reservationManager->selectRoom($idRoom);

        $date = new DateTime();
        $today = $date->format("Y-m-d");
        $tomorrow = $date->add(DateInterval::createFromDateString("1 day"))->format("Y-m-d");
        $maxDate = $date->add(DateInterval::createFromDateString("1 year"))->format("Y-m-d");

        return $this->twig->render("Admin/planning.html.twig", [
            "customers" => $customers,
            "today" => $today,
            "maxDate" => $maxDate,
            "tomorrow" => $tomorrow,
            "idRoom" => $idRoom,
            "name" => $room['name'],
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

    public function planningAdd(int $idRoom): ?string
    {
        $this->checkAdmin();
        $reservationManager = new ReservationManager();

        //checking if the POST[name] is valid
        $formCheck = new FormCheck($_POST);
        $nameError = $formCheck->shortText('name');

        if ($formCheck->getValid()) {
            //check if the room is avaible during the time period
            $roomReserved = ($reservationManager->selectRoomBetween($_POST['tripStart'], $_POST['tripEnd']));
            foreach ($roomReserved as $room) {
                if ($room['id_room'] == $idRoom) {
                    header("Location:/admin/planning/$idRoom/?message=une reservation existe déjà à cette date");
                    return null;
                }
            }

            //convert the strings from the post into DateTime object in order to have all the dates in between them
            try {
                $dateStart = new DateTime($_POST['tripStart']);
            } catch (\Exception $e) {
                header("Location:/admin/planning/$idRoom/?message=mauvais format de date d'arrivée");
                return null;
            }
            try {
                $dateEnd = new DateTime($_POST['tripEnd']);
            } catch (\Exception $e) {
                header("Location:/admin/planning/$idRoom/?message=mauvais format de date de départ");
                return null;
            }

            //check if the date of departure is after the date of arrival
            if ($dateStart > $dateEnd) {
                header("Location:/admin/planning/$idRoom/?message=Erreur: date départ avant date arrivée");
                return null;
            }
            $dateDiff = date_diff($dateStart, $dateEnd);
            $oneDay = new DateInterval("P1D");
            $dates[1] = $dateStart->format("Y-m-d");

            //generate all the dates in between the reservation into an array
            for ($i = $dateDiff->d; $i > 1; $i--) {
                $dates[$i] = $dateStart->add($oneDay)->format("Y-m-d");
            }

            //add all the dates from the previous array into the database
            foreach ($dates as $date) {
                $reservationManager->add($idRoom, $_POST['name'], $date);
            }
            header("Location:/admin/planning/$idRoom/?message=La réservation a bien été ajoutée");
            return null;
        }
        header("Location:/admin/planning/$idRoom/?message=$nameError");
        return null;
    }

    public function editListPrice(): string
    {
        $this->checkAdmin();
        $priceManager = new PriceManager();
        $prices = $priceManager->selectAll();
        return $this->twig->render('Admin/editListPrice.html.twig', ['prices' => $prices]);
    }

    public function editPrice(int $id): ?string
    {
        $this->checkAdmin();
        $priceEdit = new PriceManager();
        $price = $priceEdit->selectOneById($id);
        $priceNameError = $priceSummerError = $priceWinterError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formEditCheck = new FormCheck($_POST);
            $priceNameError = $formEditCheck->shortText('name');
            $priceSummerError = $formEditCheck->number('price_summer');
            $priceWinterError = $formEditCheck->number('price_winter');
            if ($formEditCheck->getValid()) {
                $priceEdit->UpdatePrice($_POST);
                header('Location:/admin/editPrice/' . $_POST['id'] . '/?message=La catégorie prix a bien été modifiée');
                return null;
            }
        }
        return $this->twig->render('Admin/editPrice.html.twig', [
            'price' => $price,
            'priceWinterError' => $priceWinterError,
            'priceSummerError' => $priceSummerError,
            'priceNameError' => $priceNameError,
        ]);
    }

    public function editListTheme(): ?string
    {
        $this->checkAdmin();
        $themeManager = new ThemeManager();
        $themes = $themeManager->selectAll();
        return $this->twig->render('Admin/editListTheme.html.twig', ['themes' => $themes]);
    }

    public function editTheme(int $id): ?string
    {
        $this->checkAdmin();
        $themeEdit = new ThemeManager();
        $theme = $themeEdit->selectOneById($id);
        $themeNameError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formEditCheck = new FormCheck($_POST);
            $themeNameError = $formEditCheck->shortText('name');
            if ($formEditCheck->getValid()) {
                $themeEdit->UpdateTheme($_POST);
                header('Location:/admin/editTheme/' . $_POST['id'] . '/?message=Catégorie thème a été modifiée');
                return null;
            }
        }
        return $this->twig->render('Admin/editTheme.html.twig', [
            'theme' => $theme,
            'themeNameError' => $themeNameError,
        ]);
    }

    public function addTheme(): ?string
    {
        $this->checkAdmin();
        $themeManager = new ThemeManager();
        $theme = $themeManager->selectAll();
        $themeNameError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formCheck = new FormCheck($_POST);
            $themeNameError = $formCheck->shortText('name');
            if ($formCheck->getValid()) {
                $themeManager = new ThemeManager();
                $themeManager->insert($_POST);
                header('Location:/Admin/editListTheme/?message=Un nouveau thème a bien été ajouté');
                return null;
            }
        }
        return $this->twig->render('Admin/addTheme.html.twig', [
            'theme' => $theme,
            'themeNameError' => $themeNameError,
            'post' => $_POST,
        ]);
    }

    public function addPrice(): ?string
    {
        $this->checkAdmin();
        $priceManager = new PriceManager();
        $price = $priceManager->selectAll();
        $priceNameError = $priceSummerError = $priceWinterError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formCheck = new FormCheck($_POST);
            $priceNameError = $formCheck->shortText('name');
            $priceSummerError = $formCheck->number('priceSummer');
            $priceWinterError = $formCheck->number('priceWinter');
            if ($formCheck->getValid()) {
                $priceManager = new PriceManager();
                $priceManager->insert($_POST);
                header('Location:/Admin/editListPrice/?message=Une nouvelle catégorie de prix a bien été créée');
                return null;
            }
        }
        return $this->twig->render('Admin/addPrice.html.twig', [
            'price' => $price,
            'priceWinterError' => $priceWinterError,
            'priceSummerError' => $priceSummerError,
            'priceNameError' => $priceNameError,
            'post' => $_POST,
        ]);
    }

    public function editListView(): ?string
    {
        $this->checkAdmin();
        $viewManager = new ViewManager();
        $views = $viewManager->selectAll();
        return $this->twig->render('Admin/editListView.html.twig', ['views' => $views]);
    }

    public function addView(): ?string
    {
        $this->checkAdmin();
        $viewManager = new ThemeManager();
        $view = $viewManager->selectAll();
        $viewNameError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formCheck = new FormCheck($_POST);
            $viewNameError = $formCheck->shortText('name');
            if ($formCheck->getValid()) {
                $viewManager = new ViewManager();
                $viewManager->insert($_POST);
                header('Location:/Admin/editListView/?message=Une nouvelle vue a bien été ajoutée');
                return null;
            }
        }
        return $this->twig->render('Admin/addView.html.twig', [
            'view' => $view,
            'viewNameError' => $viewNameError,
            'post' => $_POST,
        ]);
    }

    public function editView(int $id): ?string
    {
        $this->checkAdmin();
        $viewEdit = new ViewManager();
        $view = $viewEdit->selectOneById($id);
        $viewNameError = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formEditCheck = new FormCheck($_POST);
            $viewNameError = $formEditCheck->shortText('name');
            if ($formEditCheck->getValid()) {
                $viewEdit->UpdateView($_POST);
                header('Location:/admin/editView/' . $_POST['id'] . '/?message=La catégorie vue a bien été modifiée');
                return null;
            }
        }
        return $this->twig->render('Admin/editView.html.twig', [
            'view' => $view,
            'viewNameError' => $viewNameError,
        ]);
    }

    public function deleteView(int $id): void
    {
        $this->checkAdmin();
        $viewManager = new ViewManager();
        $deleteViewOption = true;
        try {
            $viewManager->deleteView($id);
        } catch (\PDOException $e) {
            $deleteViewOption =false;
        }
        if ($deleteViewOption) {
            header("Location:/admin/editListView/?message=La vue a bien été supprimée");
        } else {
            header("Location:/admin/editListView/?message=Vue non supprimée car utilisée par des chambres");
        }
    }

    public function deleteTheme(int $id): void
    {
        $this->checkAdmin();
        $themeManager = new ThemeManager();
        $deleteThemeOption = true;
        try {
            $themeManager->deleteTheme($id);
        } catch (\PDOException $e) {
            $deleteThemeOption = false;
        }
        if ($deleteThemeOption) {
            header("Location:/admin/editListTheme/?message=Le thème a bien été supprimé");
        } else {
            header("Location:/admin/editListTheme/?message= Thème non supprimé car utilisé pour des chambres");
        }
    }

    public function deletePrice(int $id): void
    {
        $this->checkAdmin();
        $priceManager = new PriceManager();
        $deletePriceOption = true;
        try {
            $priceManager->deletePrice($id);
        } catch (\PDOException $e) {
            $deletePriceOption = false;
        }
        if ($deletePriceOption) {
            header("Location:/admin/editListPrice/?message=La catégorie prix a bien été supprimée");
        } else {
            header("Location:/admin/editListPrice/?message=Prix non supprimé car utilisé pour des chambres");
        }
    }
}
