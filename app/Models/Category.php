<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{


 /**
  * Get the route key for the model.
  *
  * @return string
  */

    protected $fillable = ['name','category_image'];

    public function product() {
      return $this->hasMany(Product::class);
    }
}
