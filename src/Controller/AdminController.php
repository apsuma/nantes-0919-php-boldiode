<?php

namespace App\Controller;

use App\Model\AdminManager;
use App\Model\RoomManager;

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
                header("Location:/admin/editlist");
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
        return $this->twig->render('Room/editList.html.twig', ['roomList' => $roomList]);
    }
}
