<?php

// Inclure la classe Participant

require_once __DIR__ . '/../model/Participant.php';

class ParticipantController
{

    public function getParticipants()
    {
        $participants = Participant::findAll();

        if (!$participants) {
            return null;
        }

        return $participants;
    }
    public function getParticipant($id)
    {

        $participant = Participant::findById($id);

        if (!$participant) {
            return null;
        }

        return $participant;
    }

    public function createParticipant($lastname, $firstname, $society)
    {
        $participant = new Participant($lastname, $firstname, $society);
        $participant->create();
    }
}
