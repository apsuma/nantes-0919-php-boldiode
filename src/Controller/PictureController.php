<?php

namespace App\Controller;

use App\Model\PictureManager;
use App\Service\ImageUploader;

class PictureController extends AbstractController
{
    /**
     * @param int $id
     * @param int $idRoom
     */
    public function delete(int $id, int $idRoom): void
    {
        $pictureManager = new PictureManager();
        $imageDeleter = new ImageUploader();
        $picture = $pictureManager->selectOneById($id);
        $imageDeleter->delete($picture['image']);
        $pictureManager->deletePictureId($id, $idRoom);
        header('location:/admin/edit/'.$idRoom);
    }
}
