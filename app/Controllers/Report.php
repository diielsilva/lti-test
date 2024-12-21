<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\CustomResponse;
use App\Dtos\ResponseBody;
use App\Models\SpentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Report extends BaseController
{
    public function index()
    {
        return view('pages/reports');
    }

    public function create()
    {

        $request = $this->getRequestBody();
        $response = new ResponseBody([]);
        $authenticatedUserId = $this->getCurrentAuthenticatedUserId();

        //Verifying if it is missing a required field, user should choose at least one mode
        if (!isset($request["startDate"]) && !isset($request["endDate"]) && !isset($request["categoryId"])) {
            $response->message = "You must choose a report mode";
            return $this->json(400, $response);
        }

        //Verifying if all dates (start and end) are setted, when one date is present (start or end)
        if ((isset($startDate) && !isset($endDate)) || (isset($endDate) && !isset($startDate))) {
            $response->message = "The period of time is invalid";
            return $this->json(400, $response);
        }

        $startDate = $request["startDate"];
        $endDate = $request["endDate"];
        $categoryId = $request["categoryId"];

        //TODO Verify if end date is smaller than start date...

        //Getting spents by a specific period of time
        if (!empty($startDate) && !empty($endDate) && empty($categoryId)) {
            //query by period
            $model = new SpentModel();

            $builder = $model->db->table('spents');

            $sql = "spents.created_at BETWEEN DATE('{$startDate}') AND DATE('{$endDate}') AND spents.user_id = {$authenticatedUserId}";

            $res = $builder->select('spents.value, spents.created_at, spents.description, categories.name')
                ->join('categories', 'categories.id = spents.category_id')
                ->join('users', 'users.id = spents.user_id')
                ->where($sql)
                ->get()
                ->getResult();

            foreach ($res as $item) {
                $response->content[] = $item;
            }

            $response->content = $res;

            return $this->json(200, $response);
        }

        //Getting spents by category
        if (empty($startDate) && empty($endDate) && isset($categoryId)) {

            //query by category
            $model = new SpentModel();

            $builder = $model->db->table('spents');

            $sql = "spents.category_id = {$categoryId} AND users.id = {$authenticatedUserId}";

            $res = $builder->select('spents.value, spents.created_at, spents.description, categories.name')
                ->join('categories', 'categories.id = spents.category_id')
                ->join('users', 'users.id = spents.user_id')
                ->where($sql)
                ->get()
                ->getResult();

            foreach ($res as $item) {
                $response->content[] = $item;
            }

            $response->content = $res;

            return $this->json(200, $response);
        }

        //Getting spents by category and a period of time
        if (isset($startDate) && isset($endDate) && isset($categoryId)) {
            //query by period and category
            $model = new SpentModel();

            $builder = $model->db->table('spents');

            $sql = "spents.created_at BETWEEN DATE('{$startDate}') AND DATE('{$endDate}') AND spents.category_id = {$categoryId} AND spents.user_id = {$authenticatedUserId}";

            $res = $builder->select('spents.value, spents.created_at, spents.description, categories.name')
                ->join('categories', 'categories.id = spents.category_id')
                ->join('users', 'users.id = spents.user_id')
                ->where($sql)
                ->get()
                ->getResult();

            foreach ($res as $item) {
                $response->content[] = $item;
            }

            $response->content = $res;

            return $this->json(200, $response);
        }
    }
}
