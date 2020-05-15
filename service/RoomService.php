<?php
include_once '../repository/RoomRepository.php';
include_once '../config/Database.php';
include_once '../object/Room.php';
include_once '../object/Building.php';

/**
 * Klasa posrednia pomiedzy otrzymaniem danych a wstawieniem ich do bazy danych
 * Obsluguje wszystko zwiazane z pokojami
 */

class RoomService
{

    /**
     * Funkcja prosi repozytorium aby dodalo nowy pokoj do bazy
     * @param array $data dane dodawanego pokoju
     */

    public static function addNew($data)
    {
        if(!empty($data->name) && !empty($data->building))
        {
            $room = new Room();
            $room->setName($data->name);

            $building = new Building();
            $building->setId($data->building);

            $room->setBuilding($building);

            //init database
            $database = new Database();
            $db = $database->getConnection();

            $rr = new RoomRepository($db);

            if($rr->addNew($room))
            {
                http_response_code(201);
                echo json_encode(array("message" => "Room created successfully", "id" => $room->getId()));
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create room. Service temporarily unavailable."));
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create room. The data is incomplete."));
        }
    }


}