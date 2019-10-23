<?php

/**
 * Room manager, interact with the table room in the boldiode database
 */

namespace App\Model;

class RoomManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'room';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
