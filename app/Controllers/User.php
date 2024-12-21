<?php

namespace App\Controllers;

use App\Dtos\ResponseBody;
use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        $authenticatedUser = session()->get("online_user");

        //Passing data to fulfill the update user form 
        $data = [
            "name" => $authenticatedUser["name"],
            "email" => $authenticatedUser["email"]
        ];

        return view('pages/users/index', $data);
    }

    public function update()
    {
        $request = $this->getRequestBody();
        $response = new ResponseBody([]);

        //Verifying if it is missing a required field
        if (empty($request["name"]) || empty($request["email"]) || empty($request["password"])) {
            $response->message = "Missing required fields";
            return $this->json(400, $response);
        }

        $name = $request["name"];
        $email = $request["email"];
        $password = $request["password"];

        //Verifying if email is invalid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response->message = "Invalid email";
            return $this->json(400, $response);
        }

        //Verifying if password is below minimum length
        if (strlen($password) < 6) {
            $response = "Password must have 6 characters";
            return $this->json(400, $response);
        }

        $model = new UserModel();
        $authenticatedUserId = $this->getCurrentAuthenticatedUserId();
        $hasUserWithEmail = $model->where("email", $email)->first();

        //Verifying if the new email is in use by other user
        if ($hasUserWithEmail && $hasUserWithEmail["id"] !== $authenticatedUserId) {
            $response->message = "Email in use";
            return $this->json(409, $response);
        }

        //Updating user, with an ID, because this way, Codeignite performs an update instead of an insert
        $model->save([
            "id" => $authenticatedUserId,
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        //Updating user session, it is necessary to automatically fullfill the inputs of update user form
        $userSession = session()->get("online_user");
        $userSession["name"] = $name;
        $userSession["email"] = $email;

        $response->message = "User updated";
        return $this->json(200, $response);
    }

    public function delete()
    {
        $authenticatedUserId = $this->getCurrentAuthenticatedUserId();
        $response = new ResponseBody([]);

        $model = new UserModel();

        //Verifying if the user exists in the database (it can be redundant, because the user needs to exist to access this endpoint, but it is better to verify anyway)
        $hasUserWithId = $model->find($authenticatedUserId);

        //If the user was not found, return a 404 response
        if (!$hasUserWithId) {
            $response->message = "User not found";
            return $this->json(404, $response);
        }

        //Delete current user
        $model->delete($authenticatedUserId);

        //Destroy my session (logout)
        session()->destroy();

        return $this->json(200, $response);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to("/");
    }
}
