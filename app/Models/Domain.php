<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'organization_id',
        'subdomain',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}