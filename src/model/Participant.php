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

    public static function findAll()
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('SELECT * FROM participants');

        $statement->execute();

        $participants = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($participants) {
            return $participants;
        } else {
            return null;
        }
    }

    public static function findById($id)
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('SELECT * FROM participants WHERE id = :id');

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);


        return $row;
    }

    public static function findByFormation($formationId)
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('SELECT p.id, p.lastname, p.firstname, p.society, formation_id FROM formation_has_participants
        LEFT JOIN participants p ON p.id = participant_id
        WHERE formation_id = :formation_id;');

        $statement->bindValue(':formation_id', $formationId, PDO::PARAM_INT);

        $statement->execute();

        $participants = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $participants;
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
