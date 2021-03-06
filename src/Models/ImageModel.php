<?php

namespace ZWorkshop\Models;

/**
 * The image model.
 */
class ImageModel
{
    /**
     * The DB connection.
     *
     * @var \PDO
     */
    private $dbConnection;

    /**
     * The image model constructor.
     *
     * @param \PDO $dbConnection
     */
    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Gets all images belonging to the given user.
     *
     * @param string $username
     *
     * @return array
     */
    public function getUserCollection(string $username): array
    {
        $sql = 'SELECT `images`.IdImage, `images`.FileName, `images`.ProcessingResult '
                .'FROM `users` '
                .'JOIN `images` USING(IdUser) '
                .'WHERE `username` = :username;';
        $params = [':username' => $username];

        $query = $this->dbConnection->prepare($sql);
        $query->execute($params);

        $results = $query->fetchAll(\PDO::FETCH_ASSOC);
        usort($results, [$this, 'sortImages']);

        return $results;
    }

    /**
     * Saves a image for the user with the given id.
     *
     * @param int    $userId
     * @param string $fileName
     * @param string $emotions
     */
    public function save(int $userId, string $fileName, string $emotions): void
    {
        $sql = 'INSERT INTO `images` (`IdUser`, `FileName`, `ProcessingResult`) '
                .'VALUES (:idUser, :fileName, :processingResult)';
        $params = [
            ':idUser'           => $userId,
            ':fileName'         => $fileName,
            ':processingResult' => $emotions,
        ];

        $query = $this->dbConnection->prepare($sql);
        $query->execute($params);
    }

    /**
     * Gets the image with the given id.
     *
     * @param int $imageId
     *
     * @return array|null
     */
    public function get(int $imageId): ?array
    {
        $sql = 'SELECT * FROM `images` WHERE `IdImage` = :imageId;';
        $params = [':imageId' => $imageId];

        $query = $this->dbConnection->prepare($sql);
        $query->execute($params);

        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Deletes the image with the given id.
     *
     * @param int $imageId
     *
     * @return bool True if the image was deleted successfully.
     */
    public function delete($imageId): bool
    {
        $sql = 'DELETE FROM `images` WHERE `IdImage` = :imageId;';
        $params = [':imageId' => $imageId];

        $query = $this->dbConnection->prepare($sql);
        $query->execute($params);

        return (bool) $query->rowCount();
    }

    /**
     * Sorting method decider.
     *
     * @param array $image1
     * @param array $image2
     *
     * @return int
     */
    private function sortImages(array $image1, array $image2): int
    {
        $faces1 = json_decode($image1['ProcessingResult']);
        $faces2 = json_decode($image2['ProcessingResult']);

        switch (true) {
            case count($faces1) < count($faces2):
                return -1;

            case count($faces1) > count($faces2):
                return 1;

            default:
                return 0;
        }
    }
}
