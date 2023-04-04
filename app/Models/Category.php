<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public $fillable =[
        'title',
        'parent_id'
    ];

    protected $table = 'category';

    public function childs() {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    use HasFactory;
}
