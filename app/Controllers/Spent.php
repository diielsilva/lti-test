<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\ResponseBody;
use App\Models\SpentModel;

class Spent extends BaseController
{
    public function index()
    {
        return view('pages/spents');
    }

    public function create()
    {
        $responseBody = new ResponseBody([]);
        $onlineUser = session()->get('online_user')["id"];
        $spentToCreate = $this->getRequestBody();

        $categoryId = $spentToCreate["category_id"];
        $value = $spentToCreate["value"];
        $description = $spentToCreate["description"];
        $createdAt = $spentToCreate["created_at"];

        if (!isset($categoryId) || !isset($value) || !isset($description) || !isset($createdAt)) {
            $responseBody->message = "Missing required fields";

            return $this->json(400, $responseBody);
        }

        //Verify if the spent value is greater than zero
        if ($value <= 0) {
            $responseBody->message = "Spent value must be greater than zero";

            return $this->json(400, $responseBody);
        }

        if (strlen($description) < 1) {
            $responseBody->message = "Spent description must have at least 1 character";

            return $this->json(400, $responseBody);
        }

        //Convert date to SQL format
        $createdAtTimestamps = strtotime($createdAt);
        $createdAt = date('Y-m-d H:i:s', $createdAtTimestamps);

        $model = new SpentModel();

        $model->save([
            "user_id" => $onlineUser,
            "category_id" => $categoryId,
            "value" => $value,
            "description" => $description,
            "created_at" => $createdAt
        ]);

        $responseBody->content[] = "Spent created";

        return $this->json(201, $responseBody);
    }

    public function findByUser()
    {
        $onlineUser = session()->get('online_user')["id"];
        $responseBody = new ResponseBody([]);

        $model = new SpentModel();

        $spents = $model->where("user_id", $onlineUser)->findAll();

        foreach ($spents as $spent) {
            $responseBody->content[] = $spent;
        }

        return $this->json(200, $responseBody);
    }

    public function update()
    {
        $requestBody = $this->getRequestBody();
        $responseBody = new ResponseBody([]);

        $spentId = $requestBody["spentId"];
        $value = $requestBody["value"];
        $createdAt = $requestBody["createdAt"];
        $categoryId = $requestBody["categoryId"];
        $description = $requestBody["description"];

        if (!isset($spentId) || !isset($value) || !isset($createdAt) || !isset($categoryId) || !isset($description)) {
            $responseBody->message = "Missing required fields";

            return $this->json(400, $responseBody);
        }

        if ($value < 1) {
            $responseBody->message = "Invalid value";

            return $this->json(400, $responseBody);
        }

        $createdAtTimestamps = strtotime($createdAt);
        $createdAt = date('Y-m-d H:i:s', $createdAtTimestamps);

        $model = new SpentModel();

        $model->save([
            "id" => $spentId,
            "value" => $value,
            "created_at" => $createdAt,
            "category_id" => $categoryId,
            "description" => $description,
            "updated_at" => date('Y-m-d H:i:s')
        ]);

        $responseBody->content[] = "Spent updated";

        return $this->json(200, $responseBody);
    }

    public function delete()
    {
        $requestBody = $this->getRequestBody();
        $spentId = $requestBody["spentId"];

        $model = new SpentModel();

        $model->delete($spentId);

        return $this->json(204, null);
    }
}
