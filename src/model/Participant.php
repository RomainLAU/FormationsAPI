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

        // Préparer une requête SQL pour récupérer l'enregistrement avec l'ID spécifié
        $statement = $pdo->prepare('SELECT * FROM participants WHERE id = :id');

        // Lier les valeurs aux marqueurs de paramètres
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécuter la requête
        $statement->execute();

        // Récupérer les données de l'enregistrement sous forme de tableau associatif
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'enregistrement a été trouvé
        if ($row) {
            // Créer un nouvel objet Participant
            $participant = new Participant($row['lastname'], $row['firstname'], $row['society']);

            // Retourner l'objet Participant
            return $participant;
        }

        // Si aucun enregistrement n'a été trouvé, retourner null
        return null;
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
