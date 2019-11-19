<?php

namespace App\Model;

class ReservationSearchManager extends AbstractManager
{
    const TABLE = "reservation_search";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function add(string $start, string $end): void
    {
        $reservationManager = new ReservationManager();
        $reservedRoom = $reservationManager->selectRoomBetween($start, $end);
        foreach ($reservedRoom as $room) {
            $query = "INSERT INTO " . self::TABLE . " (id_room) VALUES (" .$room['id_room'] . ")";
            $this->pdo->query($query);
        }
    }

    public function clear(): void
    {
        $query ="DELETE FROM " . self::TABLE;
        $this->pdo->query($query);
    }
}
