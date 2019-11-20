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
            $heavyPass = password_hash($pwd, PASSWORD_BCRYPT);
            $statement->bindValue(":pwd", $heavyPass, \PDO::PARAM_STR);
            $statement->execute();
            return "a été ajouté";
        }
        return "existe déjà";
    }

    public function selectByName(string $name): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE login=:name";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":name", $name, \PDO::PARAM_STR);
        $admin = $statement->execute();
        $admin = $statement->fetch();
        if ($admin == false) {
            $admin = [];
        }
        return $admin;
    }

    public function selectAllOrderByNameFront(string $front = null): array
    {
        $query = 'SELECT * FROM room';
        if (isset($front) && !empty($front) && $front == 'front') {
            $query .= ' ORDER BY front_page DESC, name';
        } else {
            $query .= ' ORDER BY name';
        }
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
