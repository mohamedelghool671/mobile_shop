<?php

namespace App\Providers;


use App\Interfaces\AddressReposityInterface;
use Laravel\Sanctum\Sanctum;
use App\Reposities\CartReposity;
use App\Reposities\UserReposity;
use App\Reposities\OrderReposity;
use App\Reposities\CommentReposity;
use App\Reposities\ContactReposity;
use App\Reposities\ProductReposity;
use App\Reposities\ProfileReposity;
use App\Reposities\CategoryReposity;
use App\Reposities\FavouriteReposity;
use App\Interfaces\FavouriteInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use App\Interfaces\CartReposityInterface;
use App\Interfaces\OrderReposiyInterface;
use App\Interfaces\UserReposityInterface;
use App\Interfaces\CommentReposiyInterface;
use App\Interfaces\ContactReposiyInterface;
use App\Interfaces\ProfileReposiyInterface;
use App\Interfaces\ProductReposityInterface;
use App\Interfaces\CategoryReposityInterface;
use App\Interfaces\CobonReposityInterface;
use App\Reposities\AddressReposity;
use App\Reposities\CobonReposity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserReposityInterface::class,UserReposity::class);
        $this->app->bind(ProductReposityInterface::class,ProductReposity::class);
        $this->app->bind(CartReposityInterface::class,CartReposity::class);
        $this->app->bind(CategoryReposityInterface::class,CategoryReposity::class);
        $this->app->bind(CommentReposiyInterface::class,CommentReposity::class);
        $this->app->bind(ContactReposiyInterface::class,ContactReposity::class);
        $this->app->bind(OrderReposiyInterface::class,OrderReposity::class);
        $this->app->bind(ProfileReposiyInterface::class,ProfileReposity::class);
        $this->app->bind(FavouriteInterface::class, FavouriteReposity::class);
        $this->app->bind(AddressReposityInterface::class, AddressReposity::class);
        $this->app->bind(CobonReposityInterface::class, CobonReposity::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
