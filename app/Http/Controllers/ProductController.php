<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products= Product::where('status', 'active')->get();
        return view('products.index', compact('products'));
    }

    public function create(){
        return view('products.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        Product::create([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return redirect()->route('product.index');
    }
}
