<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryIndex(){
        $categories = Category::where('parent_id', '=', 0)->get();
        $allCategories = Category::all();
        return view('tree.categoryView', [
            'categories' => $categories,
            'allCategories' => $allCategories
        ]);
    }

    public function addCategory(Request $request){
        $this->validate($request,[
            'title'=>'required|max:50',
        ]);
        $elem = $request->all();

        $elem['parent_id'] = empty($elem['id']) ? 0: $elem['id'];

        Category::create($elem);


        return $elem;
    }

    public function addCategoryForm(Request $request){
        $this->validate($request,[
            'title_new'=>'required|max:50',
        ]);
        $elem = $request->all();
        $elem['title'] = $request->title_new;
        $elem['parent_id'] = 0;

        Category::create($elem);
        return back();
    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:50',
        ]);

        $category = Category::findOrFail($request->id);
        $category->title = $request->title;
        $category->save();

        return $category;
    }

    public function deleteCategory(Request $request){
        $category = Category::find($request->id);

        $deleteCategory_parentId = $category->parent_id;
        $category_parent = Category::where('parent_id', '=', $category->id)->get();

        foreach ($category_parent as $cp){
            if($request->type == "all"){
                $cp->delete();
            }else if ($request->type == "none"){
                $cp->parent_id = $deleteCategory_parentId;
                $cp->update();
            }
        }

        $category->delete();

        return $category;


    }
    public function editCategoryParent(Request $request){
        $category = Category::find($request->id);

        $parent = Category::with('childs')->where('id', $request->new_parent)->get();

            if ($parent[0]['parent_id'] == $request->id) {
                return "Bad thing";
            } else {
                $category->parent_id = $request->new_parent;

                $category->update();

                return $category;
            }
    }

}
