<?php

namespace App\Controller;

use App\Model\AdminManager;

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
                echo "coucou";
            } else {
                echo "pas coucou";
            }
        }
    }
}
