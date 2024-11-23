<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Models\Product;

class WebController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $major_categories = MajorCategory::all();

        // 最近の商品の取得（レビューの平均スコアを含める）
        $recently_products = Product::withAvg('reviews', 'score')
                                    ->orderBy('created_at', 'desc')
                                    ->take(4)
                                    ->get();

        // おすすめ商品の取得（レビューの平均スコアを含める）
        $recommend_products = Product::withAvg('reviews', 'score')
                                     ->where('recommend_flag', true)
                                     ->take(3)
                                     ->get();

        // 注目商品の取得（レビューの平均スコアを含める）
        $featured_products = Product::withAvg('reviews', 'score')
                                    ->orderBy('reviews_avg_score', 'desc')
                                    ->take(4)
                                    ->get();

        return view('web.index', compact('major_categories', 'categories', 'recently_products', 'recommend_products', 'featured_products'));
    }
}
