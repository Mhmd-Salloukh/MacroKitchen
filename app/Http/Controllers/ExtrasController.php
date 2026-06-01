<?php

namespace App\Http\Controllers;
use App\Models\Extra;
use Illuminate\Http\Request;
use App\Models\Item;

class ExtrasController extends Controller
{
    public function ManageExtras()
    {
        $extras = Extra::all();
        return view('admin.extras', compact('extras'));
    }

    public function CreateExtra(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'calories' => 'nullable|integer|min:0',
            'proteins' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fats' => 'nullable|integer|min:0',
        ]);

        Extra::create($fields);
        return redirect()->route('admin.extras')->with('success', 'Extra created successfully.');
    }

    public function EditExtra(Extra $extra)
    {
        return view('admin.edit_extras', compact('extra'));
    }

    public function UpdateExtra(Request $request, Extra $extra)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'calories' => 'nullable|integer|min:0',
            'proteins' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fats' => 'nullable|integer|min:0',
        ]);

        $extra->update($fields);
        return redirect()->route('admin.extras')->with('success', 'Extra updated successfully.');
    }

    public function DeleteExtra(Extra $extra)
    {
        $extra->delete();
        return redirect()->route('admin.extras')->with('success', 'Extra deleted successfully.');
    }

    public function AddItemExtra(Request $request, Item $item)
    {
         $fields = $request->validate([
            'extra_id' => 'required|exists:extras,id'
        ]);

        if($item->extras->contains($fields['extra_id'])) {
            return redirect()->back()->with('error', 'Extra already associated with this item.');
        }

        $item->extras()->attach($fields['extra_id']);

        return redirect()->back()->with('success', 'Extra added to item successfully.');
    }
    
    public function RemoveItemExtra(Item $item, Extra $extra)
    {
        if(!$item->extras->contains($extra->id)) {
            return redirect()->back()->with('error', 'Extra not associated with this item.');
        }
        $item->extras()->detach($extra->id);

        return redirect()->back()->with('success', 'Extra removed from item successfully.');
    }
}
