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

    public function selectRoomById(int $id)
    {
        $query = "SELECT r.name, r.description, r.nb_bed, r.surface, r.front_page, p.price_summer, p.price_winter, ";
        $query .= "p.name, v.name, t.name FROM room r INNER JOIN price p ON r.id_price = p.id ";
        $query .= "INNER JOIN view v ON r.id_view = v.id ";
        $query .= "INNER JOIN theme t ON r.id_theme = t.id ";
        $query .= "WHERE r.id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
