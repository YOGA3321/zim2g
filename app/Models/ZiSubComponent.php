<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZiSubComponent extends Model
{
    protected $fillable = ['zi_component_id', 'code', 'name'];

    public function component()
    {
        return $this->belongsTo(ZiComponent::class, 'zi_component_id');
    }

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }
}
