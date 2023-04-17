<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryIndex(Request $request){
        if($request->sort_type == "none" || $request->sort_type == null){
            session()->put('sort_type','none');
            $categories = Category::where('parent_id', '=', 0)->get();
        }else if($request->sort_type == "order"){
            $categories = Category::where('parent_id', '=', 0)->orderBy('title')->get();
            session()->put('sort_type', $request->sort_type);
        }else if($request->sort_type == "desc"){
            $categories = Category::where('parent_id', '=', 0)->orderBy('title', 'desc')->get();
            session()->put('sort_type', $request->sort_type);
        }

        return view('tree.categoryView', [
            'categories' => $categories,
        ]);
    }
    public function addCategory(Request $request){
        $this->validate($request,[
            'title'=>'required|max:50',
        ]);

        $elem = $request->all();

        $check_elem = Category::where("parent_id" , '=' , $elem['id'])->max('sort_id');

        $elem['parent_id'] = empty($elem['id']) ? 0: $elem['id'];
        $elem['sort_id'] = ($check_elem + 1);

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

    public function editCategory(Request $request){
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
        $tree = [];
        $category = Category::find($request->id);
        $check_parent = Category::find($request->new_parent);

        while($check_parent){
            array_unshift($tree, $check_parent->id);
            $check_parent = $check_parent->parent;
        }

        for($i = 0 ; $i < count($tree) ; $i++) {
            if ($tree[$i] == $request->id) {
                return "Nie możesz przenieść tego folderu w to miejsce!";
            }
        }

        $category->parent_id = $request->new_parent;
        $category->update();

        return $category;
    }

    public function specialSorting(Request $request){

        session()->put('sort_type','special');
        session()->put('sort_key', $request->sort_letter);

        $categories = Category::where('parent_id', '=', 0)->where('title', 'LIKE', $request->sort_letter .'%')->get();

        $categories_rest = Category::where('parent_id', '=', 0)->where('title', 'NOT LIKE', $request->sort_letter .'%')->get();

        $categories_union = $categories->merge($categories_rest);

        return view('tree.categoryView', [
            'categories' => $categories_union,
        ]);
    }

    public function arrowSorting(Request $request){
        session()->put('sort_type', 'arrow');

        $categories = Category::where('parent_id', '=', 0)->orderBy('sort_id')->get();

        return view('tree.categoryView', [
            'categories' => $categories,
        ]);
    }

}
