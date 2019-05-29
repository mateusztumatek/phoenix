<?php

use Illuminate\Database\Seeder;
use App\Product;

class SeedGalleryTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i<30; $i++){
            $rand = rand(1,14);
            $product = Product::inRandomOrder()->first();
            \App\Gallery::create([
                'product_id' => $product->id,
                'name' => $faker->name,
                'image' => 'osoby/'.$rand.'.jpg'
            ]);
        }
    }
}
