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

    /**
     * @param array $room
     * @return int
     */
    public function insert(array $room): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO ". self::TABLE .
            "(name,description,nb_bed,surface,id_price,id_view,id_theme) 
            VALUES (:name,:description,:nb_bed,:surface,:id_price,:id_view,:id_theme)");
        $statement->bindValue('name', $room['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $room['description'], \PDO::PARAM_STR);
        $statement->bindValue('nb_bed', $room['nb_bed'], \PDO::PARAM_INT);
        $statement->bindValue('surface', $room['surface'], \PDO::PARAM_INT);
        $statement->bindValue('id_price', $room['id_price'], \PDO::PARAM_INT);
        $statement->bindValue('id_view', $room['id_view'], \PDO::PARAM_INT);
        $statement->bindValue('id_theme', $room['id_theme'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
