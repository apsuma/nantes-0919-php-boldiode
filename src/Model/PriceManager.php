<?php

namespace App\Model;

class PriceManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'price';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $price): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(name,price_summer,price_winter) 
            VALUES (:name,:price_summer,:price_winter)");
        $statement->bindValue('name', $price['name'], \PDO::PARAM_STR);
        $statement->bindValue('price_summer', $price['priceSummer'], \PDO::PARAM_INT);
        $statement->bindValue('price_winter', $price['priceWinter'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function updatePrice(array $price): bool
    {
        $query = "UPDATE " . self::TABLE .
            " SET name = :name,
            price_winter = :price_winter,
            price_summer = :price_summer
            WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $price['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $price['name'], \PDO::PARAM_STR);
        $statement->bindValue('price_summer', $price['price_summer'], \PDO::PARAM_INT);
        $statement->bindValue('price_winter', $price['price_winter'], \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function deletePrice(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
    }
}
