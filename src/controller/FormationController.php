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

        return $formation;
    }

    public function addParticipantToFormation($formationId, $participantId)
    {
        $formation = Formation::findById($formationId);
        $participantToAdd = Participant::findById($participantId);

        foreach ($formation['participants'] as $key => $participant) {
            if (!$participantToAdd || $participantId == $participant['id'] || count($formation['participants']) >= $formation['max_participants']) {
                return 409;
            }
        }

        if ($formationId && $participantId) {
            $formation = Formation::addParticipant($formationId, $participantId);

            return $formation;
        }
    }

    public function removeParticipantOfFormation($formationId, $participantId)
    {
        $formation = Formation::findById($formationId);

        foreach ($formation['participants'] as $key => $participant) {
            if ($participantId == $participant['id']) {
                $formation = Formation::removeParticipant($formationId, $participant['id']);


                return $formation;
            }
        }

        return 400;
    }

    public function deleteFormation($formationId)
    {
        $formation = Formation::findById($formationId);


        if ($formation) {
            $formation = Formation::removeFormation($formationId);


            return $formation;
        }

        return 400;
    }
}
