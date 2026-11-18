<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ["organization_id", "name", "description", "code", "status"];
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function doctors() {
        ///  the doctor model dont exisit yet
    }
}
