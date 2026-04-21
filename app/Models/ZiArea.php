<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZiArea extends Model
{
    protected $fillable = ['code', 'name'];

    public function components()
    {
        return $this->hasMany(ZiComponent::class);
    }
}
