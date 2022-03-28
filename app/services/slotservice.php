<?php

namespace Services;

use DateTime;
use Models\Appointment;

use Services\AppointmentService;

class SlotService
{
    private $appointmentService;

    // initialize services
    function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }

    public function getSlotsByDate($date)
    {
        // $date = new DateTime($data);
        $opening = clone $date;
        $opening->setTime(9, 0, 0);
        $closing = clone $date;
        $closing->setTime(18, 0, 0);
        $duration = 45;
        $break = 15;

        $taken = $this->appointmentService->getAllByDate($date);
        $timeslots = $this->getTimeslots($opening, $closing, $duration, $break);
        foreach ($timeslots as $slot1) {
            foreach ($taken as $slot2) {
                if ($slot1->timeslot == $slot2->timeslot) {
                    $slot1->taken = true;
                    break;
                } else {
                    $slot1->taken = false;
                }
            }
        }

        return $timeslots;
    }
    private function getTimeslots($opening, $closing, $duration, $break)
    {
        $timeslots = [];
        $start = clone $opening;
        $closing = clone $closing;
        $end = clone $opening;
        $end->modify("+{$duration} minutes");

        $i = 0;
        do {
            $appointment = new Appointment();
            $appointment->start = $start;
            $appointment->end = clone $end;
            $appointment->duration = $duration;
            $appointment->timeslot = $i;
            $appointment->taken = false;

            $timeslots[$i] = clone $appointment;
            $i++;

            $end->modify("+{$break} minutes");
            $start = clone $end;
            $end->modify("+{$duration} minutes");
        } while ($end <= $closing);

        return $timeslots;
    }
}
