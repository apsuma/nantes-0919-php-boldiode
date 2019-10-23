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
