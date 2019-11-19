<?php

namespace App\Service;

class ImageUploader
{
    const PUBLIC_PATH = "/uploads/images/";
    const COMPLETE_PATH = __DIR__."/../../public".self::PUBLIC_PATH;

    public function uploadImage(string $file, string $ext): string
    {
        $rawFilename = uniqid('image') . '.' . $ext;
        $filename = strtolower(str_replace(' ', '', $rawFilename));
        move_uploaded_file($file, self::COMPLETE_PATH.$filename);
        return $filename;
    }

    public function delete(string $file): void
    {
        $route = self::COMPLETE_PATH . $file;
        if (file_exists($route)) {
            unlink($route);
        }
    }
}
