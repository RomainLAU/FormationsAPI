<?php

// Inclure la classe Formation

require_once __DIR__ . '/../model/Formation.php';

class FormationController
{
    public function getFormations()
    {
        $formations = Formation::findAll();

        if (!$formations) {
            return null;
        }

        return $formations;
    }

    public function getFormation($id)
    {

        $formation = Formation::findById($id);

        if (!$formation) {
            return null;
        }

        return $formation;
    }

    public function createFormation($name, $start_date, $end_date, $max_participants, $price)
    {
        $formation = new Formation($name, $start_date, $end_date, $max_participants, $price);
        $formation->create();
    }
}
