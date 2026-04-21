<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZiComponent extends Model
{
    protected $fillable = ['zi_area_id', 'code', 'name'];

    public function area()
    {
        return $this->belongsTo(ZiArea::class, 'zi_area_id');
    }

    public function subComponents()
    {
        return $this->hasMany(ZiSubComponent::class, 'zi_component_id');
    }
}
