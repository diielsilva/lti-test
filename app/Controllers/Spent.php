<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\CustomResponse;
use App\Models\SpentModel;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class Spent extends BaseController
{
    public function index()
    {
        return view('pages/spents');
    }

    public function create()
    {
        $responseBody = new CustomResponse([], []);
        $spentToCreate = $this->getRequestBody();

        $categoryId = $spentToCreate["category_id"];
        $value = $spentToCreate["value"];
        $description = $spentToCreate["description"];
        $createdAt = $spentToCreate["created_at"];

        if (!isset($categoryId) || !isset($value) || !isset($description) || !isset($createdAt)) {
            $responseBody->errors[] = "Missing required fields";

            return $this->json(400, $responseBody);
        }

        //Verify if the spent value is greater than zero
        if ($value <= 0) {
            $responseBody->errors[] = "Spent value must be greater than zero";

            return $this->json(400, $responseBody);
        }

        if (strlen($description) < 1) {
            $responseBody->errors[] = "Spent description must have at least 1 character";

            return $this->json(400, $responseBody);
        }

        //Convert date to SQL format
        $createdAtTimestamps = strtotime($createdAt);
        $createdAt = date('Y-m-d H:i:s', $createdAtTimestamps);

        $model = new SpentModel();

        $model->save([
            "category_id" => $categoryId,
            "value" => $value,
            "description" => $description,
            "created_at" => $createdAt
        ]);

        $responseBody->content[] = "Spent created";

        return $this->json(201, $responseBody);
    }
}
