<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function products() {
        return $this->hasMany(ProductVariant::class);
    }

    public function pdv() {
        return $this->products()->select(DB::raw("DISTINCT variant"));

    }

}
