<?php

namespace App\Model;

/**
 * Picture manager, interact with the table picture in the boldiode database
 */
class PictureManager extends AbstractManager
{
    /**
     * Const necessary for the parent construct (Abstract manager)
     */
    const TABLE = 'picture';

    /**
     * Initialize this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $picture
     * @param int $roomId
     * @param string $filename
     */
    public function insert(array $picture, int $roomId, string $filename): void
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            "(id_room,image,description) 
            VALUES (:id_room,:image,:description)");
        $statement->bindValue('id_room', $roomId, \PDO::PARAM_INT);
        $statement->bindValue('image', $filename, \PDO::PARAM_STR);
        $statement->bindValue('description', $picture['description'], \PDO::PARAM_STR);
        $statement->execute();
    }

    public function deleteRoomId(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id_room=:id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * @param int $id
     * @param int $idRoom
     */
    public function deletePictureId(int $id, int $idRoom): void
    {
        if (count($this->selectPicturesByRoom($idRoom)) > 1) {
            $query = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
            $query->bindValue('id', $id, \PDO::PARAM_INT);
            $query->execute();
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function selectPicturesByRoom(int $id): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE id_room= :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array $pictures
     */
    public function updatePicturesByRoom(array $pictures): void
    {
        foreach ($pictures as $keyPicture => $picture) {
            if (preg_match("#^image-#", $keyPicture)) {
                $idPicture = preg_replace("#(image-)([0-9]*)#", '$2', $keyPicture);
                $idPicture = intval($idPicture);
                $query = $this->pdo->prepare("UPDATE " . self::TABLE . " SET image = :image
                    WHERE id = :idPicture");
                $query->bindValue('image', $picture, \PDO::PARAM_STR);
                $query->bindValue('idPicture', $idPicture, \PDO::PARAM_INT);
                $query->execute();
            }
        }
    }
}
