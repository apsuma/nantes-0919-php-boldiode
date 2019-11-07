<?php

namespace App\Model;

/**
 * Room manager, interact with the table room in the boldiode database
 */
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
        $query = "SELECT 
            r.id roomId, 
            r.name roomName,
            r.description,
            r.nb_bed nbBed, 
            r.surface,
            r.front_page frontPage,
            r.id_view roomViewId, 
            r.id_theme roomThemeId,
            r.id_price roomPriceId, 
            p.price_summer priceSummer,
            p.price_winter priceWinter,
            p.name priceName, 
            v.name viewName,
            t.name themeName 
            FROM " . self::TABLE . " r
            INNER JOIN price p ON r.id_price = p.id 
            INNER JOIN view v ON r.id_view = v.id 
            INNER JOIN theme t ON r.id_theme = t.id 
            INNER JOIN picture ON picture.id_room = r.id 
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

    public function selectAllRooms($nbBed = 0, $idPrice = 0): array
    {
        $query = "SELECT
            r.id roomId,
            r.name roomName,
            r.description,
            r.nb_bed nbBed,
            r.surface,
            price.price_summer priceSummer,
            price.price_winter priceWinter,
            view.name viewName,
            theme.name themeName
            FROM " . self::TABLE . " r
            JOIN price ON r.id_price = price.id
            JOIN view ON r.id_view = view.id
            JOIN theme ON r.id_theme = theme.id
            WHERE r.nb_bed >= $nbBed";
        if ($idPrice != 0) {
            $query .= " AND price.id = $idPrice";
        }
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateRoom(array $room): bool
    {
        if (!isset($room['front_page'])) {
            $room['front_page'] = 0;
        }
        // prepared request
        $query = "UPDATE " . self::TABLE .
            " SET name = :name,
            description = :description,
            surface = :surface,
            nb_bed = :nbBed,
            front_page = :frontPage,
            id_price = :priceId,
            id_view = :viewId,
            id_theme = :themeId 
            WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $room['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $room['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $room['description'], \PDO::PARAM_STR);
        $statement->bindValue('surface', $room['surface'], \PDO::PARAM_INT);
        $statement->bindValue('nbBed', $room['nb_bed'], \PDO::PARAM_INT);
        $statement->bindValue('frontPage', $room['front_page'], \PDO::PARAM_INT);
        $statement->bindValue('priceId', $room['id_price'], \PDO::PARAM_INT);
        $statement->bindValue('viewId', $room['id_view'], \PDO::PARAM_INT);
        $statement->bindValue('themeId', $room['id_theme'], \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function delete(int $id)
    {
        $query =$this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
    }

    public function maxBed()
    {
        $query = "SELECT MAX(nb_bed) maxBed FROM " . self::TABLE;
        $bed = $this->pdo->query($query)->fetch();
        return $bed['maxBed'];
    }
}
