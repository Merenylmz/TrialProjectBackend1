<?php

namespace App\Models;

use App\Events\ViewsCountEvent;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    protected $fillable = [
        "title",
        "description",
        "categoryId",
        "userId",
        "views"
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    protected static function booted(){
        parent::boot();

        static::retrieved(function($model){
            event(new ViewsCountEvent($model));
        });
    }
    
}
