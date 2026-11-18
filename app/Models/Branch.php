<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'address',
        'status',
        "code"
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
