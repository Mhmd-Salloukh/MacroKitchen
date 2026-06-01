<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class CategoryController extends Controller
{
       public function ManageCategories()
    {
        $categories = Category::all();
        
        return view('admin.categories', compact('categories'));
    }

    public function CreateCategory(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Category::create($fields);
        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function EditCategory(Category $category)
    {
        return view('admin.edit_category', compact('category'));
    }

    public function UpdateCategory(Category $category, Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category->update($fields);
        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

     public function AddItemCategory(Item $item, Request $request)
    {
        $fields = $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        if($item->categories->contains($fields['category_id'])) {
            return redirect()->back()->with('error', 'Category already associated with this item.');
        }

        $item->categories()->attach($fields['category_id']);

        return redirect()->back()->with('success', 'Category added to item successfully.');
    }

    public function RemoveItemCategory(Item $item, Category $category)
    {
        if(!$item->categories->contains($category->id)) {
            return redirect()->back()->with('error', 'Category not associated with this item.');
        }
        $item->categories()->detach($category->id);

        return redirect()->back()->with('success', 'Category removed from item successfully.');
    }
}
