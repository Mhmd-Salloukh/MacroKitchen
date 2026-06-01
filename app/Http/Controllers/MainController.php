<?php

namespace App\Http\Controllers;


use App\Models\Item;
use App\Models\Category;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Http\Request;


class MainController extends Controller
{
    public function index()
    {
        $items= Item::inRandomOrder()->limit(9)->get();
        
        return view('main.home', compact('items'));
    }

   public function Menu()
{
    $categories = Category::with('items')->get();
    return view('main.menu', compact('categories'));
}
}