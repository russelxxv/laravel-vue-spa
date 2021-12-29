<?php

namespace Database\Seeders;

use App\Models\Bookable;
use Illuminate\Database\Seeder;

class BookablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 50)->create()->each(function ($user) {
        //     $user->posts()->save(factory(App\Post::class)->make());
        // });
        // factory(Bookable::class, 100)->create();
        Bookable::factory(100)->create();
    }
}
