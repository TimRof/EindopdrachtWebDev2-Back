<?php

namespace Controllers;

use Exception;
use DateTime;
use Services\AppointmentService;
use Services\SlotService;
use Services\TypeService;

class AppointmentController extends Controller
{
    private $service;
    private $slotService;
    private $typeService;

    // initialize services
    function __construct()
    {
        $this->service = new AppointmentService();
        $this->slotService = new SlotService();
        $this->typeService = new TypeService();
    }

    public function getAll()
    {
        $jwt = $this->checkForToken();
        if (!$jwt)
            return;

        try {
            if (isset($_GET["epoch"]) && is_numeric($_GET["epoch"])) {
                $epoch = $_GET["epoch"];
            }

            $date = new DateTime();
            $date->setTimestamp($epoch);

            $appointments = $this->service->getAll($date);

            $this->respond($appointments);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    // for demo purposes only: pagination
    public function getAllv2()
    {
        if ($this->checkForAdmin() != 1) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        try {
            if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
                $offset = $_GET["offset"];
            }
            if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
                $limit = $_GET["limit"];
            }

            $appointments = $this->service->getAllv2($offset = NULL, $limit = NULL);

            $this->respond($appointments);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
    // for demo purposes only: filtering
    public function getAllv3()
    {
        if ($this->checkForAdmin() != 1) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        try {
            if (isset($_GET["query"])) {
                $query = $_GET["query"];
            }

            $appointments = $this->service->getAllv3($_GET["query"]);

            $this->respond($appointments);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getOne($id)
    {
        if ($this->checkForAdmin() != 1) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }
        try {
            $appointment = $this->service->getOne($id);

            if (!$appointment) {
                $this->respondWithError(404, "Appointment not found");
                return;
            }

            $this->respond($appointment);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function create()
    {
        $jwt = $this->checkForToken();
        if (!$jwt)
            return;
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $post = json_decode(file_get_contents('php://input'));

                $date = new DateTime();
                $date->setTimestamp($post->date);
                $timeslots = $this->slotService->getSlotsByDate($date);
                // get timeslot
                foreach ($timeslots as $s) {
                    if ($s->timeslot == $post->timeslot) {
                        $timeslot = clone $s;
                    }
                }
                // check if taken
                if ($timeslot->taken == true) {
                    return $this->respondWithError(400, "Bad Request");
                }
                $appointment = $this->service->insert($post->type, $timeslot, $post->id);
                if ($appointment) {
                    return $this->respond($appointment);
                } else {
                    return $this->respondWithError(500, "Internal Server Error");
                }
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function update($id)
    {
        if ($this->checkForAdmin() != 1) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }
        try {
            $type = $this->createObjectFromPostedJson("Models\\Type");
            if ($this->service->update($id, $type->type)) {
                return $this->respond("OK");
            } else {
                return $this->respondWithError(500, "Internal Server Error");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function delete($id)
    {
        if ($this->checkForAdmin() != 1) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }
        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}
