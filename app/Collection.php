<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Collection extends Model
{
    protected $fillable = ['name', 'intro', 'background', 'display_on_home', 'active'];
    protected $table = 'collections';

    public function delete()
    {
        DB::table('collection_items')->where('collection_id', $this->id)->delete();
        return parent::delete();
    }
}
