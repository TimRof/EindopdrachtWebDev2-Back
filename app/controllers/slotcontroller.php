<?php

namespace Controllers;

use Exception;
use DateTime;
use Services\AppointmentService;
use Services\SlotService;

class SlotController extends Controller
{
    private $appointmentService;
    private $slotService;

    // initialize services
    function __construct()
    {
        $this->appointmentService = new AppointmentService();
        $this->slotService = new SlotService();
    }

    public function getSlotsByDate($epoch)
    {
        $jwt = $this->checkForToken();
        if (!$jwt)
            return;
        $date = new DateTime();
        $date->setTimestamp($epoch);

        $timeslots = $this->slotService->getSlotsByDate($date);

        $this->respond($timeslots);
    }
}
