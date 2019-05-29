<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class cleanspaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:cleanspaces';

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
        $tags = DB::connection('prod')->table('product_tags')->get();
        foreach($tags as $tag){
            $t = str_replace(' ','', $tag->tag);
            $t = str_replace('\t','', $t);
            DB::connection('prod')->table('product_tags')->where('id', $tag->id)->update([
                'tag' => $t
            ]);
        }
    }
}
