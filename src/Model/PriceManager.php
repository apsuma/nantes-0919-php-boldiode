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
}
