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

    public static function findById($id)
    {
        $pdo = new PDO('mysql:host=localhost:3306;dbname=formations', 'root', 'tiger');

        // Préparer une requête SQL pour récupérer l'enregistrement avec l'ID spécifié
        $statement = $pdo->prepare('SELECT * FROM formations WHERE id = :id');

        // Lier les valeurs aux marqueurs de paramètres
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécuter la requête
        $statement->execute();

        // Récupérer les données de l'enregistrement sous forme de tableau associatif
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'enregistrement a été trouvé
        if ($row) {
            // Créer un nouvel objet Formation
            $formation = new Formation($row['name'], $row['start_date'], $row['end_date'], $row['max_participants'], $row['price']);

            // Retourner l'objet Formation
            return $formation;
        }

        // Si aucun enregistrement n'a été trouvé, retourner null
        return null;
    }

    public function create()
    {
        $pdo = new PDO('mysql:host=localhost:3306;dbname=formations', 'root', 'tiger');

        $statement = $pdo->prepare('INSERT INTO formations (name, start_date, end_date, max_participants, price) VALUES (:name, :start_date, :end_date, :max_participants, :price)');

        $statement->bindValue(':name', $this->name, PDO::PARAM_STR_CHAR);
        $statement->bindValue(':start_date', date('d-m-Y', strtotime($this->start_date)), PDO::PARAM_STR);
        $statement->bindValue(':end_date', date('d-m-Y', strtotime($this->end_date)), PDO::PARAM_STR);
        $statement->bindValue(':max_participants', $this->max_participants, PDO::PARAM_INT);
        $statement->bindValue(':price', $this->price, PDO::PARAM_INT);

        $statement->execute();
    }
}
