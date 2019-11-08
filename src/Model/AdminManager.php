<?php

namespace App\Model;

class AdminManager extends AbstractManager
{
    const TABLE = "admin";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function add($name, $pwd): string
    {
        if (!$this->selectByName($name)) {
            $query = "INSERT INTO " . self::TABLE . " (login, pwd) VALUES (:login, :pwd)";
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(":login", $name, \PDO::PARAM_STR);
            $heavyPass = password_hash($pwd, PASSWORD_ARGON2I);
            $statement->bindValue(":pwd", $heavyPass, \PDO::PARAM_STR);
            $statement->execute();
            return "a été ajouté";
        }
        return "existe déjà";
    }

    public function selectByName(string $name): bool
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE login=:name";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":name", $name, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
