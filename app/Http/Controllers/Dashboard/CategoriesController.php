<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $request = request();

        // SELECT a.*, b.name as parent_name
        // FROM categories as a
        // LEFT JOIN categories as b ON b.id = a.parent_id

        $categories = Category::with('parent')
            /*leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])*/
            //->select('categories.*')
            //->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id AND status = 'active') as products_count')
            //->addSelect(DB::raw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count'))
            
            // ->withCount('products as products_number')
            ->withCount([
                'products as products_number' => function($query) {
                    $query->where('status', '=', 'active');
                }
            ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate(5);

        // $request->query() vetch the array from the URL

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        $parents = Category::all();
        return view('dashboard.categories.create', compact('category', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(Category::rules());

        $clean_data = $request->validate(Category::rules(), [
            'required' => 'This field (:attribute) is required',
            'name.unique' => 'This name is already exists!'
        ]);

        // $request->input('name');
        // $request->query('name');
        // $request->get('name');
        // $request->post('name');
        // $request->name;
        // $request['name'];

        // $request->all();
        // $request->only('image', 'status');
        // $request->except('name', 'parent_id');
        
        // Request merge
        $request->merge([
            'slug' => str::slug($request->post('name')),
        ]);
        // $validated = $request->safe()->merge(['slug' => str::slug($request->post('name'))]);

        // كل القيم التي في الطلب ماعدا الصورة
        $data = $request->except('image');
        
        //To store the new value of image variable
        $data['image'] = $this->uploadImgae($request); //append a new data to the variable data;

        // Mass assignment
        $category = Category::create($data);

        // PRG
        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record not found!');
        }

        // SELECT * FROM categories WHERE id <> $id 
        // AND (parent_id IS NULL OR parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', '<>', $id);
            })
            ->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);

        $old_image = $category->image;

        $data = $request->except('image');
        $new_image = $this->uploadImgae($request);
        if ($new_image) {
            $data['image'] = $new_image;
        }

        $category->update($data);
        //$category->fill($request->all())->save();

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return Redirect::route('dashboard.categories.index')
            ->with('info', 'Category updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // $category = Category::findOrFail($id);

        $category->delete();

        //Category::where('id', '=', $id)->delete();
        //Category::destroy($id);

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category deleted!');
    }

    protected function uploadImgae(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image'); // UploadedFile Object

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;

        // if($request->hasFile('image')){
        //     $file = $request->file('image'); // Uploadedfile object
        //     // three disk ( local - public - external)
        //     // 1- local disk is in the folder app in the folder storage.
        //     // 2- public disk is in the folder public in the folder app in the folder storage.
        //     // 3- external disk (s3) is in the server like amazon.
        //     // look at the filesystems.php in the folder config
        //     $path = $file->store('uploads', [   //uploads: named of folder
        //         'disk' => 'public'              //the disk uses to store file
        //     ]);

        //     //To cotrol the name of file
        //     // $path = $file->storeAs('uploads', $nameFile, [   //uploads: named of folder
        //     //     'disk' => 'public'              //the disk uses to store file
        //     // ]);

        //     // the function of merge don't change the value of request if is image has contain value, but keep the orginal value of variable image.
        //     // $request->merge([
        //     //     'image' => $path,
        //     // ]);
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category restored!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category['image']);
        }

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category deleted forever!');
    }
}
