<?php


namespace App\Model;

class ThemeManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'theme';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
