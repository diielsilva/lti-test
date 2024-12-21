<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dtos\ResponseBody;
use App\Models\CategoryModel;

class Category extends BaseController
{
    public function index()
    {
        return view("pages/categories/index");
    }

    public function create()
    {
        $authenticatedUserId = $this->getCurrentAuthenticatedUserId();
        $request = $this->getRequestBody();
        $response = new ResponseBody([]);

        //Verifying if it is missing a required field
        if (empty($request["name"])) {
            $response->message = "Missing required fields";
            return $this->json(400, $response);
        }

        $name = $request["name"];

        $model = new CategoryModel();

        $hasCategoryWithName = $model->where(["user_id" => $authenticatedUserId, "name" => $name])->first();

        //Verifying if the user already has a category with the same name
        if ($hasCategoryWithName) {
            $response->message = "Category name is in use";
            return $this->json(409, $response);
        }

        //Creating category
        $model->save([
            "user_id" => $authenticatedUserId,
            "name" => $name,
            "created_at" => date('Y-m-d H:i:s')
        ]);

        $response->message = "Category saved";
        return $this->json(201, $response);
    }

    public function findByUser()
    {
        $authenticatedUserId = $this->getCurrentAuthenticatedUserId();
        $response = new ResponseBody([]);

        $model = new CategoryModel();

        //Getting all categories that belongs to the authenticated user
        $categories = $model->where("user_id", $authenticatedUserId)->findAll();

        //Fulfill the response body with categories
        foreach ($categories as $category) {
            $response->content[] = $category;
        }

        return $this->json(200, $response);
    }

    public function update()
    {
        $request = $this->getRequestBody();
        $response = new ResponseBody([]);
        $authenticatedUserId = $this->getCurrentAuthenticatedUserId();

        //Verify if there is a missing required field
        if (empty($request["id"]) || empty($request["name"])) {
            $response->message = "Missing required fields";
            return $this->json(400, $response);
        }

        $id = $request["id"];
        $newName = $request["name"];

        $model = new CategoryModel();

        $hasAnotherCategoryWithNewName = $model->where(["name" => $newName, "user_id" => $authenticatedUserId])->first();

        //Verify if online user has other category with the same name
        if ($hasAnotherCategoryWithNewName && $hasAnotherCategoryWithNewName["id"] !== $id) {
            $response->message = "Category name in use";
            return $this->json(409, $response);
        }

        //Updating category
        $model->save([
            "id" => $id,
            "name" => $newName,
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        return $this->json(200, null);
    }

    public function delete()
    {
        $request = $this->getRequestBody();
        $response = new ResponseBody([]);

        //Verifying if it is missing required fields
        if (empty($request["id"])) {
            $response->message = "Missing required fields";
            return $this->json(400, $response);
        }

        $id = $request["id"];

        $model = new CategoryModel();

        //Deleting category
        $model->delete(["id" => $id]);

        return $this->json(200, $response);
    }
}
