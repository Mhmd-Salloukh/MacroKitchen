<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Extra;
use App\Models\ItemImage;

class ItemController extends Controller
{
    public function CreateItem(Request $request)
{
    try {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'calories' => 'required|integer|min:0',
            'proteins' => 'required|integer|min:0',
            'carbs' => 'required|integer|min:0',
            'fats' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000'
        ]);

        if (isset($fields['image'])) {
            $imageName = time() . '.' . $fields['image']->extension();
            $fields['image']->move(public_path('/assets/images'), $imageName);
            $fields['image'] = $imageName;
        }

        Item::create($fields);

        return redirect()->back()->with('success', 'Item created successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['general' => $e->getMessage()]);
    }
}

public function ManageItems()
    {
        $items = Item::all();
        $deletedItems = Item::onlyTrashed()->get();
        $extras= Extra::all();
        return view('admin.items', compact('items', 'deletedItems'));
    }


    public function EditItem(Item $item)
    {   $categories = Category::whereNotIn("id", $item->categories()->pluck('category_id'))->get();
        $extras = Extra::whereNotIn("id", $item->extras()->pluck('extra_id'))->get();
        return view('admin.edit_item', compact('item', 'categories', 'extras'));
    }

    public function UpdateItem(Item $item, Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => 'required|string|max:255',
                'base_price' => 'required|numeric|min:0',
                'calories' => 'required|integer|min:0',
                'proteins' => 'required|integer|min:0',
                'carbs' => 'required|integer|min:0',
                'fats' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000'
            ]);

            if (isset($fields['image'])) {
                $imageName = time() . '.' . $fields['image']->extension();
                $fields['image']->move(public_path('/assets/images'), $imageName);
                $fields['image'] = $imageName;
            }

            $item->update($fields);

            return redirect()->route('admin.items')->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withError(['general' => $e->getMessage()]);
        }
    }

    public function DeleteItem(Item $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function RestoreItem(int $id)
    {   $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();
        return redirect()->back()->with('success', 'Item restored successfully.');
    }
    
    public function PermanentDeleteItem(int $id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->forceDelete();
        return redirect()->back()->with('success', 'Item permanently deleted successfully.');
    }
}