<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemChild extends Model
{
    protected $fillable = ['parent_id', 'child_id'];

    public function itemChild()
    {
        return $this->hasOne(Item::class, 'child_id');
    }
}
