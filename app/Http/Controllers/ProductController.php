<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if($request->category !== null) {
            $products = Product::where('category_id', $request->category)->sortable()->paginate(16);
            $total_count = Product::where('category_id', $request->category)->count();
            $category = Category::find($request->category);
            $major_category = MajorCategory::find($category->major_category_id);
        } elseif ($keyword !== null) {
            $products = Product::where('name', 'like', "%{$keyword}%")->sortable()->paginate(16);
            $total_count = $products->total();
            $category = null;
            $major_category = null;
        } else {
            $products = Product::sortable()->paginate(16);
            $total_count = "";
            $category = null;
            $najor_category = null;
        }

        $categories = Category::all();
        $major_categories = MajorCategory::all();

        return view('products.index', compact('products', 'category', 'major_category', 'categories', 'major_categories', 'total_count', 'keyword'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->save();

        return to_route('products.index');
    }

    public function show(Product $product)
    {
        $reviews = $product->reviews()->get();
        return view('products.show', compact('product', 'reviews'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id'); // 修正箇所
        $product->update();

        return to_route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return to_route('products.index');
    }
}