<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {$request = request();
        $this->authorize('view-any', Product::class);
        // SELECT * FROM products
        // SELECT * FROM categories WHERE id IN (..)
        // SELECT * FROM stores WHERE id IN (..)
        $products = Product::with(['category', 'store'])->filter($request->query())->paginate();
        
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Product::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('view', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update( $request->except('tags') );
        
        $tags = json_decode($request->post('tags'));
        
        $tag_ids = [];
        
        $saved_tags = Tag::all();

        foreach ($tags as $item) {
            $slug = Str::slug($item->value);
            $tag = $saved_tags->where('slug', $slug)->first();
            if (!$tag) {
                $tag = Tag::create([
                    'name' => $item->value,
                    'slug' => $slug,
                ]);
            }
            $tag_ids[] = $tag->id;
        }

        $product->tags()->sync($tag_ids);
        // $product->tags()->attach($tag_ids); فقط تضيف في الجدول الوسيط (غير مضمونة لانها قد تضيف شي موجود مسبقا)
        // $product->tags()->detach($tag_ids); تحدف من الجدول الوسيط
        // $product->tags()->syncWithoutDetaching($tag_ids); تضيف الى الجدول الوسيط ولا تحدف الموجود مسبقا

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated');
    } 
// public function update(Request $request, Product $product)
// {
//     $product->update($request->except('tags'));
//     $tags =  explode(',' , $request->post('tags')); //turn string to array
//     $tag_ids=[];
//     foreach ($tags as $t_name){
//         $slug = Str::slug($t_name);
//       $tag =  Tag::firstOrCreate([ 'slug' => $slug],['name' => $t_name ]); //search if there are model matching create new one with given parameters
//         $tag_ids[] = $tag->id; //get ids of tags inserted
//     }
//     $product->tags()->sync($tag_ids); //sync searches if there are matches records if found dousnt delete the matched record if it has record not found in table will insertt it and if it doesnt have a record which exists in table delete it from table
//     return redirect()->back()->with(['success' => 'Product updated']);
// }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('delete', $product);
    }
}
