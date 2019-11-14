<?php

namespace App\Service;

class ImageUploader
{
    const PUBLIC_PATH = "/uploads/images/";
    const COMPLETE_PATH = __DIR__."/../../public".self::PUBLIC_PATH;
    const ALLOWED_EXTENSION = ['jpg', 'jpeg', 'png', 'gif'];

    public function uploadImage(string $file): string
    {
        $rawFilename = uniqid('image') . '.jpg';
        $filename = strtolower(str_replace(' ', '', $rawFilename));
        move_uploaded_file($file, self::COMPLETE_PATH.$filename);
        return $filename;
    }
}
