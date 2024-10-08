<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'category_id', 'store_id',
        'price', 'compare_price', 'status',
    ];

    protected $hidden = [
        'image', 'created_at', 'updated_at', 'deleted_at',
    ];

    protected $appends = [
        'image_url',
    ];

    protected static function booted(){
        // This query takes the relationship so it is slower to select the data but faster to insert the data than code without relationship
        // static::addGlobalScope('store', function(Builder $builder){
        //     $user = Auth::user();
        //     if($user->store_id){
        //         $builder->where('store_id', '=', $user->store_id);
        //     }
        // });
        // static::addGlobalScope(function(Builder $builder){
        //     $user = Auth::user();
        //     if($user->store_id){
        //         $builder->where('store_id', '=', $user->store_id);
        //     }
        // });
        static::addGlobalScope('store', new StoreScope());

        static::creating(function(Product $product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,     // Related Model
            'product_tag',  // Pivot table name
            'product_id',   // FK in pivot table for the current model
            'tag_id',       // FK in pivot table for the related model
            'id',           // PK current model
            'id'            // PK related model
        );
    }

    public function scopeActive(Builder $builder){
        $builder->where('status', '=', 'active');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSFg5aURnzHFBBSQr8O7VHyJPazZBC9baVDXQ&usqp=CAU';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }


    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'store_id' => null,
            'category_id' => null,
            'tag_id' => null,
            'status' => 'active',
        ], $filters);

        $builder->when($options['status'], function ($query, $status) {
            return $query->where('status', $status);
        });

        $builder->when($filters['name'] ?? false, function($builder, $value){
            $builder->where('products.name','LIKE', "%{$value}%");
        });

        $builder->when($options['store_id'], function($builder, $value) {
            $builder->where('store_id', $value);
        });
        $builder->when($options['category_id'], function($builder, $value) {
            $builder->where('category_id', $value);
        });
        $builder->when($options['tag_id'], function($builder, $value) {

            $builder->whereExists(function($query) use ($value) {
                $query->select()
                    ->from('product_tag')
                    ->whereRaw('product_id = products.id')
                    ->where('tag_id', $value);
            });
            // $builder->whereRaw('id IN (SELECT product_id FROM product_tag WHERE tag_id = ?)', [$value]);
            // $builder->whereRaw('EXISTS (SELECT 1 FROM product_tag WHERE tag_id = ? AND product_id = products.id)', [$value]);
            
            // $builder->whereHas('tags', function($builder) use ($value) {
            //     $builder->where('id', $value);
            // });
        });
    }
}
