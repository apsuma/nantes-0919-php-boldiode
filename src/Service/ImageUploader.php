<?php

namespace App\Service;

class ImageUploader
{
    const PUBLIC_PATH = "/uploads/images/";
    const COMPLETE_PATH = __DIR__."/../../public".self::PUBLIC_PATH;

    public function uploadImage(array $file): string
    {
        $filename = uniqid($file["name"], true) . '.jpg';
        move_uploaded_file($file["tmp_name"], self::COMPLETE_PATH.$filename);
        return $filename;
    }
}
