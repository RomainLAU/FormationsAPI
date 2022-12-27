<?php

require_once __DIR__ . '/../model/Participant.php';

class ParticipantController
{

    public function getParticipants()
    {
        $participants = Participant::findAll();

        return $participants;
    }

    public function getParticipant($id)
    {

        $participant = Participant::findById($id);

        return $participant;
    }

    public function createParticipant($lastname, $firstname, $society)
    {
        $participant = new Participant($lastname, $firstname, $society);
        $participant->create();
    }

    public function getParticipantsByFormation($formationId)
    {
        $participants = Participant::findByFormation($formationId);

        return $participants;
    }
}
