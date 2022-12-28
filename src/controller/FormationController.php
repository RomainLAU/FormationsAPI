<?php

require_once __DIR__ . '/../model/Formation.php';

class FormationController
{
    public function getFormations()
    {
        $formations = Formation::findAll();

        return $formations;
    }

    public function getFormation($id)
    {

        $formation = Formation::findById($id);

        return $formation;
    }

    public function createFormation($name, $start_date, $end_date, $max_participants, $price)
    {
        $formation = new Formation($name, $start_date, $end_date, $max_participants, $price);
        $formation->create();
    }

    public function addParticipantToFormation($formationId, $participantId)
    {
        $formationParticipants = Formation::findById($formationId)[1]['participants'];

        foreach ($formationParticipants as $key => $participant) {
            if ($participantId == $participant['id']) {
                return 409;
            }
        }

        if ($formationId && $participantId) {
            $formation = Formation::addParticipant($formationId, $participantId);

            return $formation;
        }
    }

    public function removeParticipantToFormation($formationId, $participantId)
    {
        $formationParticipants = Formation::findById($formationId)[1]['participants'];

        foreach ($formationParticipants as $key => $participant) {
            if ($participantId == $participant['id']) {
                $formation = Formation::removeParticipant($formationId, $participant['id']);

                return $formation;
            }
        }

        return 400;
    }
}
