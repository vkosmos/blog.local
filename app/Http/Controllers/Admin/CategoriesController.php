<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return view('admin/categories/index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('admin/categories/create');
    }

    public function store()
    {
        $this->validate(request(), [
           'title' => 'required',
        ]);
        Category::create(request()->all());
        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'title' => 'required',
        ]);

        $category = Category::find($id);
        $category->fill($req->all());
        $category->save();

        return redirect(route('categories.index'));

    }

    public function destroy($id){
        Category::find($id)->delete();
        return redirect()->route('categories.index');
    }

}
