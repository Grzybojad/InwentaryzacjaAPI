<?php
include_once '../interfaces/IService.php';
include_once '../object/Report.php';
include_once '../config/Database.php';
include_once '../repository/ReportRepository.php';

class ReportService implements IService
{

    /**
     * @inheritDoc
     */
    static function findOneById($id)
    {
        // get database connection
        $database = new Database();
        $db = $database->getConnection();

        // create a repository instance
        $rr = new ReportRepository($db);

        $report = $rr->find($id);

        if($report!=null)
        {
            //everything went OK, asset was found
            http_response_code(200);
            echo json_encode($report);
        }
        else {
            http_response_code(404); // asset was not found
            echo json_encode(["message" => "Report does not exist"]);
        }
    }

    /**
     * @inheritDoc
     */
    static function findAll()
    {
        // get database connection
        $database = new Database();
        $db = $database->getConnection();

        // create a repository instance
        $rr = new ReportRepository($db);

        $reports = $rr->findAll();

        if($reports['count']>0)
        {
            http_response_code(200);
            echo json_encode($reports["reports"]);
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "No reports were found"));
        }
    }

    /**
     * @inheritDoc
     */
    static function addNew($data)
    {
        if(
            !empty($data->name)&&
            !empty($data->room)&&
            !empty($data->create_date)&&
            !empty($data->owner))
        {
            $report = new Report();
            $report->setName($data->name);
            $report->setRoom($data->room);
            $report->setCreateDate($data->create_date);
            $report->setOwner($data->owner);

            //init database
            $database = new Database();
            $db = $database->getConnection();

            $rr = new ReportRepository($db);

            if($rr->addNew($report))
            {
                http_response_code(201);
                echo json_encode(array("message" => "Report created successfully"));
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create report. Service temporarily unavailable."));
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create report. The data is incomplete."));
        }
    }

    /**
     * @inheritDoc
     */
    static function deleteOneById($id)
    {
        // get database connection
        $database = new Database();
        $db = $database->getConnection();

        // create a repository instance
        $rr = new ReportRepository($db);

        if($rr->deleteOne($id))
        {
            http_response_code(200);
            echo json_encode(array("message" => "Report was deleted"));
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete report. Service temporarily unavailable."));
        }
    }
}