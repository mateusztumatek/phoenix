<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $cat_count = 0;
    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i<150; $i++){
            $is_sellout = rand(0,1);
            $is_new = rand(0,1);
            $image = rand(1,13);
            if($image >= 8) $image = ['random-images/'.$image.'.jpg'];
            else $image = ['random-images/'.$image.'.png'];

            $price = $faker->randomNumber(2);

            $product = \App\Product::create([
                'name' => $faker->name,
                'intro' => $faker->realText(180),
                'content' => $faker->realText(500),
                'price' => $price,
                'prices_sellout' => ($is_sellout == 1)? $price - 5 : null,
                'new' => ($is_new)? 1 : 0,
                'size' => '12x12',
                'weight' => $faker->randomFloat(null, 0, 1),
                'profit' => null,
                'images' => json_encode($image),
                'active' => 1,
                'available' => 1,
            ]);
            $this->randomTags($product, $faker);
            $this->randomProductCategories($product, $faker);
            $this->randomProductMaterials($product, $faker);

        }
    }

    public function randomTags($product, $faker){
        $rand = rand(1,8);
        for ($i = 0; $i<$rand; $i++){
            \Illuminate\Support\Facades\DB::table('product_tags')->insert([
               'product_id' => $product->id,
                'tag' => $faker->word,
            ]);
        }
    }
    public function randomProductCategories($product, $faker){

        $rand = rand(1,3);
        for ($i = 0; $i<$rand; $i++){
            $category = \App\Category::inRandomOrder()->first();
            \Illuminate\Support\Facades\DB::table('product_categories')->insert([
                'product_id' => $product->id,
                'category_id' => $category->id,
            ]);
        }
    }

    public function randomProductMaterials($product, $faker){

        $rand = rand(1,5);
        for ($i = 0; $i<$rand; $i++){
            $material = \App\Material::inRandomOrder()->first();
            \Illuminate\Support\Facades\DB::table('product_materials')->insert([
                'product_id' => $product->id,
                'name' => $material->name,
            ]);
        }
    }
}
