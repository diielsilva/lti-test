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

    public function findAllByUser()
    {
        $userId = session()->get("online_user")["id"];
        $dto = new CustomResponse([], []);

        $category = new CategoryModel();

        //GET ALL CATEGORIES BELONGNING TO ONLINE USER
        $userCategories = $category->where("user_id", $userId)->findAll();

        //INSERTING CATEGORIES IN RESPONSE BODY
        if ($userCategories) {
            foreach ($userCategories as $category) {
                $dto->content[] = $category;
            }
        }

        return ResponseHelper::send(200, $this->response, $dto);
    }

    public function update()
    {
        $requestBody = $this->getRequestBody();
        $responseBody = new CustomResponse([], []);
        $onlineUser = session()->get("online_user");

        $categoryId = $requestBody["id"];
        $categoryName = $requestBody["name"];

        //Verify if there is a missing required field
        if (!isset($categoryId) || !isset($categoryName) || strlen($categoryName) < 1) {
            $responseBody->errors[] = "Missing required fields";

            return $this->json(400, $responseBody);
        }

        $model = new CategoryModel();

        $hasAnotherCategoryWithNewName = $model->where(["name" => $categoryName, "user_id" => $onlineUser["id"]])->first();

        //Verify if online user has other category with the same name
        if ($hasAnotherCategoryWithNewName && $hasAnotherCategoryWithNewName["id"] !== $categoryId) {
            $responseBody->errors[] = "Category name in use";

            return $this->json(409, $responseBody);
        }

        $model->save([
            "id" => $categoryId,
            "name" => $categoryName,
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        return $this->json(200, null);
    }

    public function delete()
    {

        $body = json_decode($this->request->getBody(), true);
        $dto = new CustomResponse([], []);

        $categoryId = $body["id"];

        if (!isset($categoryId)) {
            $dto->errors[] = "Missing required fields";

            return ResponseHelper::send(404, $this->response, $dto);
        }

        $category = new CategoryModel();

        $category->delete(["id" => $categoryId]);

        return ResponseHelper::send(204, $this->response, $dto);
    }
}
