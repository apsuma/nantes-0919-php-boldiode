<?php

namespace App\Controller;

use App\Model\PictureManager;

class PictureController extends AbstractController
{
    /**
     * @param int $id
     * @param int $idRoom
     */
    public function delete(int $id, int $idRoom): void
    {
        $pictureManager = new PictureManager();
        $pictureManager->deletePictureId($id, $idRoom);
        header('location:/admin/edit/'.$idRoom);
    }
}
