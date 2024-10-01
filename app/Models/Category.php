<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'parent_id', 'description', 'image', 'status', 'slug'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')
            ->withDefault([
                'name' => '-'
            ]);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    // if($name = $request->query('name')){
        //     $query->where('name','LIKE', "%{$name}%");
        // }
        // if($status = $request->query('status')){
        //     $query->whereStatus("$status");
        // }
    // public function scopeName(Builder $builder, $name){
    //     $builder->where('name', 'LIKE', "%{$name}%");
    // }

        

    public function scopeFilter(Builder $builder, $filters){
        $builder->when($filters['name'] ?? false, function($builder, $value){
            $builder->where('categories.name','LIKE', "%{$value}%");
        });
        $builder->when($filters['status'] ?? false, function($builder, $value){
            $builder->where('categories.status','=', "{$value}");
        });
    }
    
    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                // "unique:categories,name,$id",
                Rule::unique('categories', 'name')->ignore($id),

                // You can use this rule over three levels
                // 1- over the application
                'filter:php,laravel,html',
                // 2- over this model (Category)
                // new Filter(['php', 'laravel', 'html']),
                // 3- over this attribute (name)
                // function($attribute, $value, $fails) {
                //     if (strtolower($value) == 'laravel') {
                //         $fails('This name is forbidden!');
                //     }
                // },
                
            ],

            

            'parent_id' => [
                'nullable', 'int', 'exists:categories,id'
            ],
            'image' => [
                'image', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            ],
            'status' => 'required|in:active,archived',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field (:attribute) is required',
            'name.unique' => 'This name is already exists!',
        ];
    }
}
