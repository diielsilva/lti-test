<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\CustomResponse;
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
        $responseBody = new CustomResponse([], []);
        $requestBody = $this->getRequestBody();
        $userId = session()->get('online_user')["id"];

        $startDate = $requestBody["startDate"];
        $endDate = $requestBody["endDate"];
        $categoryId = $requestBody["categoryId"];

        if (!isset($startDate) && !isset($endDate) && !isset($categoryId)) {
            $responseBody->errors[] = "You must choose a report mode";

            return $this->json(400, $responseBody);
        }

        if ((isset($startDate) && !isset($endDate)) || (isset($endDate) && !isset($startDate))) {
            $responseBody->errors[] = "The period of time is invalid";

            return $this->json(400, $responseBody);
        }

        //TODO Verify if end date is smaller than start date...


        if (!empty($startDate) && !empty($endDate) && empty($categoryId)) {
            //query by period
            $model = new SpentModel();

            $builder = $model->db->table('spents');

            $sql = "spents.created_at BETWEEN DATE('{$startDate}') AND DATE('{$endDate}') AND spents.user_id = {$userId}";

            $res = $builder->select('spents.description, categories.name')
                ->join('categories', 'categories.id = spents.category_id')
                ->join('users', 'users.id = spents.user_id')
                ->where($sql)
                ->get()
                ->getResult();

            foreach ($res as $item) {
                $responseBody->content[] = $item;
            }

            $responseBody->content = $res;

            return $this->json(200, $responseBody);
        }

        if (empty($startDate) && empty($endDate) && isset($categoryId)) {

            //query by category
            $model = new SpentModel();

            $builder = $model->db->table('spents');

            $sql = "spents.category_id = {$categoryId} AND users.id = {$userId}";

            $res = $builder->select('spents.description, categories.name')
                ->join('categories', 'categories.id = spents.category_id')
                ->join('users', 'users.id = spents.user_id')
                ->where($sql)
                ->get()
                ->getResult();

            foreach ($res as $item) {
                $responseBody->content[] = $item;
            }

            $responseBody->content = $res;

            return $this->json(200, $responseBody);
        }

        if (isset($startDate) && isset($endDate) && isset($categoryId)) {
            //query by period and category
            $model = new SpentModel();

            $builder = $model->db->table('spents');

            $sql = "spents.created_at BETWEEN DATE('{$startDate}') AND DATE('{$endDate}') AND spents.category_id = {$categoryId} AND spents.user_id = {$userId}";

            $res = $builder->select('spents.description, categories.name')
                ->join('categories', 'categories.id = spents.category_id')
                ->join('users', 'users.id = spents.user_id')
                ->where($sql)
                ->get()
                ->getResult();

            foreach ($res as $item) {
                $responseBody->content[] = $item;
            }

            $responseBody->content = $res;

            return $this->json(200, $responseBody);
        }
    }
}
