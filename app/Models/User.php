<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Cashier\Billable;
use App\Observers\UserObserve;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;
#[ObservedBy([UserObserve::class])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,Billable;

    // firebase notification section
    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function getDeviceTokens()
    {
        return $this->deviceTokens()->pluck('token')->toArray();
    }

     public function routeNotificationForFcm()
    {
        return $this->getDeviceTokens();
    }


    // relations
    public function message() {
        return $this->hasMany(Message::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function favouriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favourites');
    }

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'provider_id',
        'email_verfiy_code',
        'email_verified_at',
        'email_verfiy_code_expires_at',
        'reset_code',
        'reset_code_expires_at',
        'reset_code_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function comment() {
        return $this->hasMany(Comment::class);
    }

    public function carts()
    {
        return $this->hasOne(Cart::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItems::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }
    public function contact() {
        return $this->hasMany(Contact::class);
    }

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_views')
                    ->withPivot('visit_count', 'last_visit');
    }

    public function cobons() {
        return $this->belongsToMany(Cobon::class,'user_cobon','user_id','cobon_id')->withTimestamps();
    }
}
