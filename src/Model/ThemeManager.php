<?php

namespace App\Model;

class ThemeManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'theme';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $theme): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(name) 
            VALUES (:name)");
        $statement->bindValue('name', $theme['name'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function updateTheme(array $theme): bool
    {
        $query = "UPDATE " . self::TABLE .
            " SET name = :name
            WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $theme['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $theme['name'], \PDO::PARAM_STR);
        return $statement->execute();
    }

    public function deleteTheme(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
    }
}
