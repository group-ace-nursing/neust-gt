<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = "tbladdresses";
    protected $primaryKey = "id";

    protected $allowedFields = [
        'gradId',
        'permanentAddress',
        'region',
        'province',
        'locality'
    ];
}