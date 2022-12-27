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

        $statement = $pdo->prepare("SELECT f.*, p.id AS participant_id, p.firstname, p.lastname, p.society FROM formations f
        LEFT JOIN formation_has_participants fp ON fp.formation_id = f.id
        LEFT JOIN participants p ON p.id = fp.participant_id;");

        $statement->execute();

        $formations = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $formationId = $row['id'];
            if (!isset($formations[$formationId])) {
                $formations[$formationId] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'],
                    'max_participants' => $row['max_participants'],
                    'participants' => []
                ];
            }
            if ($row['firstname']) {
                $formations[$formationId]['participants'][] = [
                    'id' => $row['participant_id'],
                    'firstname' => $row['firstname'],
                    'lastname' => $row['lastname'],
                    'society' => $row['society']
                ];
            }
        }

        return $formations;
    }

    public static function findById($id)
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare("SELECT f.*, p.id AS participant_id, p.firstname, p.lastname, p.society FROM formations f
        LEFT JOIN formation_has_participants fp ON fp.formation_id = f.id
        LEFT JOIN participants p ON p.id = fp.participant_id
        WHERE f.id = :id;");

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $formations = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $formationId = $row['id'];
            if (!isset($formations[$formationId])) {
                $formations[$formationId] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'],
                    'max_participants' => $row['max_participants'],
                    'participants' => []
                ];
            }
            if ($row['firstname']) {
                $formations[$formationId]['participants'][] = [
                    'id' => $row['participant_id'],
                    'firstname' => $row['firstname'],
                    'lastname' => $row['lastname'],
                    'society' => $row['society']
                ];
            }
        }

        return $formations;
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

        $result = $statement->execute();

        return $result;
    }

    public static function addParticipant($formationId, $participantId)
    {
        $pdo = new PDO($_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        $statement = $pdo->prepare('INSERT INTO formation_has_participants (formation_id, participant_id) VALUES (:formationId, :participantId)');

        $statement->bindValue(':formationId', $formationId, PDO::PARAM_INT);
        $statement->bindValue(':participantId', $participantId, PDO::PARAM_INT);

        $result = $statement->execute();

        return $result;
    }

    public static function removeParticipant($formationId, $participantId)
    {
    }
}
