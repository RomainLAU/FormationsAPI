<?php

class Participant
{
    public $id;
    public $lastname;
    public $firstname;
    public $society;

    public function __construct($lastname, $firstname, $society)
    {
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->society = $society;
    }

    public static function findById($id)
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('SELECT * FROM participants WHERE id = :id');

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);


        if ($row) {
            return $row;
        } else {
            return null;
        }
    }

    public function create()
    {

        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('INSERT INTO participants (lastname, firstname, society) VALUES (:lastname, :firstname, :society)');

        $statement->bindValue(':lastname', $this->lastname, PDO::PARAM_STR_CHAR);
        $statement->bindValue(':firstname', $this->firstname, PDO::PARAM_STR_CHAR);
        $statement->bindValue(':society', $this->society, PDO::PARAM_STR_CHAR);

        $statement->execute();
    }
}
