<?php

namespace App\Controller;

use App\Model\PictureManager;

class PictureController extends AbstractController
{
    public function delete(int $id, int $idRoom)
    {
        $pictureManager = new PictureManager();
        $pictureManager->deletePictureId($id);
        header('location:/admin/edit/'.$idRoom);
    }
}
