<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = "categories";
    protected $allowedFields = ["user_id", "name", "created_at", "updated_at"];
}
