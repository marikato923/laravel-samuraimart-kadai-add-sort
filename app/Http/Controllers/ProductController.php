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
    
        $sorts = [
            '新着順' => 'created_at desc',
            '価格が安い順' => 'price asc',
        ];
    
        $sort_query = [];
        $sorted = "created_at desc";
    
        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }
    
        // 商品の取得と平均評価の計算
        if ($request->category !== null) {
            $products = Product::where('category_id', $request->category)
                ->sortable($sort_query)
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            $total_count = Product::where('category_id', $request->category)->count();
            $category = Category::find($request->category);
            $major_category = MajorCategory::find($category->major_category_id);
        } elseif ($keyword !== null) {
            $products = Product::where('name', 'like', "%{$keyword}%")
                ->sortable($sort_query)
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            $total_count = $products->total();
            $category = null;
            $major_category = null;
        } else {
            $products = Product::sortable($sort_query)
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            $total_count = $products->total();
            $category = null;
            $major_category = null;
        }
    
        // 各商品の平均評価を計算
        foreach ($products as $product) {
            $product->averageScore = round($product->reviews->avg('score'), 1);  // 小数第1位で四捨五入
        }
    
        // カテゴリ情報を取得
        $categories = Category::all();
        $major_categories = MajorCategory::all();
    
        return view('products.index', compact('products', 'category', 'major_category', 'categories', 'major_categories', 'total_count', 'keyword', 'sorts', 'sorted'));
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
        $reviews = $product->reviews()->paginate(5);
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
