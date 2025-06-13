<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;
    protected $fillable = [
        'name','description','image','quantity','price','category_id','images'
    ];

    public function category() {
       return $this->belongsTo(Category::class);
    }

    public function comment() {
        return $this->hasMany(Comment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'product_views')
                    ->withPivot('visit_count', 'last_visit');
    }

    public function favouritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favourites');
    }

}
