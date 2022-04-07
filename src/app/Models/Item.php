<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['id', 'parent_id', 'by', 'title', 'time', 'type', 'text'];

    public function children(){
        return $this->hasMany(ItemChild::class, 'parent_id', 'id');
    }
}
