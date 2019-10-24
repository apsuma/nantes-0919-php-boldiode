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
        $query = "SELECT r.name roomName, r.description, r.nb_bed, r.surface, r.front_page, 
        r.id_view roomViewId, p.price_summer, p.price_winter, p.name priceName, v.id viewId, 
        v.name viewName, t.name themeName FROM room r INNER JOIN price p ON r.id_price = p.id 
        INNER JOIN view v ON r.id_view = v.id 
        INNER JOIN theme t ON r.id_theme = t.id 
        WHERE r.id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * @param array $room
     * @return int
     */
    public function insert(array $room): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
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

    public function selectAllRooms(): array
    {
        $query = "SELECT
            room.name,
            room.description,
            room.nb_bed nbBed,
            room.surface,
            price.price_summer priceSummer,
            price.price_winter priceWinter,
            view.name view,
            theme.name theme,
            picture.image picture,
            picture.description pictureDescription
            FROM room
            JOIN price ON room.id_price = price.id
            JOIN view ON room.id_view = view.id
            JOIN theme ON room.id_theme = theme.id
            JOIN picture ON picture.id_room = room.id";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
