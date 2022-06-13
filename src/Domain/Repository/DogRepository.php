<?php

namespace Domain\Repository;

use Domain\Entity\Dog;
use PDO;

class DogRepository
{
    private $connection;
    private $dataMapper;

    /**
     * @param $connection
     * @param $dataMapper
     */
    public function __construct($connection, $dataMapper)
    {
        $this->connection = $connection;
        $this->dataMapper = $dataMapper;
    }


    public function save(Dog $dog): void
    {
    }

    public function remove(Dog $dog): void
    {

    }

    public function getByID(int $id): ?Dog
    {
        $sql = "SELECT * FROM dog WHERE dog_id = :id";
        $stmt = $this->connection->prepare($sql);

        $row = $stmt->fetch();
        return $this->dataMapper->map($row);
    }
}