<?php

namespace App\Model;

class AdminManager extends AbstractManager
{
    const TABLE = "admin";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
