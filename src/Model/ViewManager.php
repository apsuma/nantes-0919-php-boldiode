<?php

namespace App\Model;

class ViewManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'view';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $view): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(name) 
            VALUES (:name)");
        $statement->bindValue('name', $view['name'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function updateView(array $view): bool
    {
        $query = "UPDATE " . self::TABLE .
            " SET name = :name
            WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $view['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $view['name'], \PDO::PARAM_STR);
        return $statement->execute();
    }

    public function deleteView(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
    }
}
