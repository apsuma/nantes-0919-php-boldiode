<?php

namespace App\Model;

class ReservationManager extends AbstractManager
{
    const TABLE = "reservation";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectRoomBetween($start, $end): array
    {
        $query = "SELECT id_room FROM " . self::TABLE . " 
            WHERE date BETWEEN '$start' AND '$end'";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectRoom(int $idRoom): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE id_room = $idRoom ORDER BY date";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteDate(int $idRoom, string $date): void
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE id_room = $idRoom AND date = '$date'";
        $this->pdo->query($query);
    }
}
