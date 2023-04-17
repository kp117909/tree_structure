<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public $fillable =[
        'title',
        'parent_id',
        'sort_id'
    ];

    protected $table = 'category';

    public function childs() {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function childs_orderBy() {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->orderBy('title');
    }

    public function childs_orderByDesc(){
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->orderBy('title', 'desc');
    }

    public function childs_specialSorting(){

        return $this->hasMany('App\Models\Category', 'parent_id', 'id')
            ->where('title', 'LIKE', session("sort_key") .'%')->
            union($this->hasMany('App\Models\Category', 'parent_id', 'id')
                ->where('title', 'NOT LIKE', session("sort_key") .'%')
            );
    }

    public function childs_arrowSorting(){
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->orderBy('sort_id');

    }

    public function childs_maxSortId(){
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->max('sort_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent_id');
    }

    public function likes() {
        return $this->hasMany(Category::class);
    }

    use HasFactory;
}
