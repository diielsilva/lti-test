<?php

namespace App\Models;

use CodeIgniter\Model;

class SpentModel extends Model
{
    protected $table = "spents";
    protected $allowedFields = ["category_id", "value", "description", "created_at", "updated_at"];
}
