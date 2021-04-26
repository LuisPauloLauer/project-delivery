<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class mdCategoriesStore extends Model
{
    protected $table = 'categoriesstore';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function allStoresByCategory()
    {
        return $this->belongsToMany( mdStores::class, 'rel_categories_stores', 'category', 'store');
    }
}
