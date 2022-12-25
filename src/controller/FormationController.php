<?php

// Inclure la classe Formation

require_once __DIR__ . '/../model/Formation.php';

class FormationController
{
    public function getFormation($id)
    {

        $formation = Formation::findById($id);

        if (!$formation) {
            $data = null;
        }

        return $data;
    }

    public function createFormation($name, $start_date, $end_date, $max_participants, $price)
    {
        $formation = new Formation($name, $start_date, $end_date, $max_participants, $price);
        $formation->create();
    }
}
