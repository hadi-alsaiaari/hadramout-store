<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    // when you wantd to renamed te colnmen of timestamps
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // when we use two or more database
    protected $connection = 'mysql';

    // when we do not write in form standared 'in migration'
    protected $table = 'stores';

    // determine the PK
    protected $primaryKey = 'id';

    // when you do not use the created and updated at in the tabled
    //public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }
}
