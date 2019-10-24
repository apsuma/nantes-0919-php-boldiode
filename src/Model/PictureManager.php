<?php

/**
 * Picture manager, interact with the table picture in the boldiode database
 */

namespace App\Model;

class PictureManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'picture';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $picture
     * @param int $roomId
     * @return void
     */
    public function insert(array $picture, int $roomId): void
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(id_room,image,description) 
            VALUES (:id_room,:image,:description)");
        $statement->bindValue('id_room', $roomId, \PDO::PARAM_INT);
        $statement->bindValue('image', $picture['image'], \PDO::PARAM_STR);
        $statement->bindValue('description', $picture['description'], \PDO::PARAM_STR);
        $statement->execute();
    }
}
