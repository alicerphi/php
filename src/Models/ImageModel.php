<?php

namespace ZWorkshop\Models;

class ImageModel
{
    /** @var \PDO */
    private $dbConnection;

    /***
     * ImageModel constructor.
     *
     * @param \PDO $dbConnection
     */
    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    //TODO: e7 - get images associated to $username

    public function save($userId, $fileName)
    {
        //TODO: e8 - save Emotion API results in db

        $sql = "INSERT INTO `images` (`IdUser`, `FileName`)
                VALUES (:idUser, :fileName)";

        $params = [
            ':idUser'           => $userId,
            ':fileName'         => $fileName,
        ];

        $query = $this->dbConnection->prepare($sql);
        $query->execute($params);

        return $query->rowCount();
    }

    /**
     * @param $imageId
     * @return mixed
     */
    public function get($imageId)
    {
        $sql = "SELECT * FROM `images`
                WHERE `IdImage` = :imageId;";
        $params = [
            ':imageId' => $imageId,
        ];

        $query = $this->dbConnection->prepare($sql);
        $query->execute($params);

        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    //TODO: e9 - delete image
}
