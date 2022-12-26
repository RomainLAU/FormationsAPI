<?php

class Formation
{
    public $id;
    public $name;
    public $start_date;
    public $end_date;
    public $max_participants;
    public $price;

    public function __construct($name, $start_date, $end_date, $max_participants, $price)
    {
        $this->name = $name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->max_participants = $max_participants;
        $this->price = $price;
    }

    public static function findAll()
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('SELECT * FROM formations');

        $statement->execute();

        $formations = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($formations) {
            return $formations;
        } else {
            return null;
        }
    }

    public static function findById($id)
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('SELECT * FROM formations WHERE id = :id');

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        }

        return null;
    }

    public function create()
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('INSERT INTO formations (name, start_date, end_date, max_participants, price) VALUES (:name, :start_date, :end_date, :max_participants, :price)');

        $statement->bindValue(':name', $this->name, PDO::PARAM_STR_CHAR);
        $statement->bindValue(':start_date', date('d-m-Y', strtotime($this->start_date)), PDO::PARAM_STR);
        $statement->bindValue(':end_date', date('d-m-Y', strtotime($this->end_date)), PDO::PARAM_STR);
        $statement->bindValue(':max_participants', $this->max_participants, PDO::PARAM_INT);
        $statement->bindValue(':price', $this->price, PDO::PARAM_INT);

        $statement->execute();
    }
}
