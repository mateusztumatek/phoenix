<?php

namespace App\Console\Commands;

use App\Material;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:cleanProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (DB::table('product_materials')->get() as $material){
           if(!Material::where('name', $material->name)->first()){
               Material::create([
                   'name' => $material->name
               ]);
           }
        }
        DB::table('product_tags')->where('id', '<', 767)->delete();
        dd('GOTOWE');
        DB::table('products')->truncate();
        DB::table('product_categories')->truncate();
        DB::table('product_materials')->truncate();
        DB::table('product_tags')->truncate();
    }
}
