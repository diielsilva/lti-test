<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\CustomResponse;
use App\Helpers\ResponseHelper;
use App\Models\CategoryModel;

class Category extends BaseController
{
    public function index()
    {
        return view("pages/categories");
    }

    public function create()
    {
        $userId = session()->get("online_user")["id"];
        $form = json_decode($this->request->getBody(), true);
        $dto = new CustomResponse([], []);

        $name = $form["name"];

        //VERIFY IF NAME DOES NOT EXIST
        if (!isset($name)) {
            $dto->errors[] = "Missing required fields";

            return ResponseHelper::send(400, $this->response, $dto);
        }

        $category = new CategoryModel();

        $userAlreadyHaveCategory = $category->where(["user_id" => $userId, "name" => $name])->first();

        //VERIFY IF USER HAS A CATEGORY WITH SAME NAME
        if ($userAlreadyHaveCategory) {
            $dto->errors[] = "Category name is in use";

            return ResponseHelper::send(409, $this->response, $dto);
        }

        //CREATE CATEGORY
        $category->save([
            "user_id" => $userId,
            "name" => $name,
            "created_at" => date('Y-m-d H:i:s')
        ]);

        $dto->content[] = "Category saved";

        return ResponseHelper::send(201, $this->response, $dto);
    }
}
