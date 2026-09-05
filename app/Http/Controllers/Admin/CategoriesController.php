<?php

namespace App\Http\Controllers\Admin;

use App\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriesRequest;

class CategoriesController extends Controller
{
    private $folder = "admin.categories.";

    public function index()
    {
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data
        ]);
    }

    public function getData()
    {
        $categories = Categories::get();
        return View($this->folder.'content',[
            'categories'=>$categories,
            'add_new' => route($this->folder.'create'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }

    public function create()
    {
        $categories = Categories::get();
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            'categories' => $categories,
            ]);
    }

    public function store(CategoriesRequest $request)
    {
        $categories = Categories::create($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'New Category created successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show(Categories $ategories)
    {
        abort(404);
    }

    public function edit(Categories $category)
    {
        $categoriesData = Categories::where("id",'<>', $category->id)->get();
    	return View($this->folder.'edit',[
    		'categories' => $category,
    		'categoriesData' => $categoriesData,
    		'form_update' => route($this->folder.'update',['category'=>$category]),
    	]);
    }

    public function update(CategoriesRequest $request, Categories $category)
    {
        $category->update($request->all());
        return redirect()->route($this->folder.'index');
        /*return response()->json([
            'status'=>true,
            'message'=>'Category '.$category->title.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);*/
    }

    public function destroy(Categories $category)
    {
        $category->delete();
        return response()->json([
                'status' => true,
                'message' => "Your Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }

    public function massDelete(Request $request)
    {
        $category = Categories::whereIn('id',$request->ids)
                        ->delete();

        return response()->json([
                'status' => true,
                'message' => "Your all Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }
}
