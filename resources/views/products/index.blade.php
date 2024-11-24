@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row">
        <div class="col-md-2">
            @component('components.sidebar', ['categories' => $categories, 'major_categories' => $major_categories])
            @endcomponent
        </div>
        <div class="col">
            @if ($category !== null)
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    </ol>
                </nav>
                <h1>{{ $category->name }}の商品一覧<span class="ms-3">{{ number_format($total_count) }}件</span></h1>
            @elseif ($keyword !== null)
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">商品一覧</li>
                    </ol>
                </nav>
                <h1>"{{ $keyword }}"の検索結果<span class="ms-3">{{ number_format($total_count) }}件</span></h1>
            @else
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">商品一覧</li>
                    </ol>
                </nav>
                <h1>商品一覧<span class="ms-3">{{ number_format($total_count) }}件</span></h1>
            @endif
            <div class="d-flex align-items-center mb-4">
                <span class="small me-2">並べ替え</span>
                <form method="GET" action="{{ route('products.index') }}">
                    @if ($category)
                        <input type="hidden" name="category" value="{{ $category->id }}">
                    @endif
                    @if ($keyword)
                        <input type="hidden" name="keyword" value="{{ $keyword }}">
                    @endif
                    <select class="form-select form-select-sm" name="select_sort" onChange="this.form.submit();">
                        @foreach ($sorts as $key => $value)
                            @if ($sorted === $value)
                                <option value="{{ $value }}" selected>{{ $key }}</option>
                            @else
                                <option value="{{ $value }}">{{ $key }}</option>
                            @endif
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('products.show', $product) }}">
                            @if ($product->image !== "")
                                <img src="{{ asset($product->image) }}" class="img-thumbnail samuraimart-product-img-products">
                            @else
                                <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-products">
                            @endif
                        </a>
                        <div class="row">
                            <div class="col-12">
                                <p class="samuraimart-product-label mt-2 mb-1">
                                    <a href="{{ route('products.show', $product) }}" class="link-dark">{{ $product->name }}</a></p>
                                    
                                      <!-- 平均評価の表示 -->      
                                    @php
                                        // 平均評価を0.5刻みに切り捨てる
                                        $averageScore = round($product->reviews->avg('score') * 2) / 2;
                                    @endphp
                                        <p class="samuraimart-star-rating mb-0" >
                                            <span class="average-star" data-rate="{{ $averageScore }}"></span>
                                            <span class="ms-2 text-muted">({{ round($averageScore, 1) ?? 'まだレビューがありません。' }})</span>
                                        </p>
                                <br>       
                                <label class="mt-1">￥{{ number_format($product->price) }}</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mb-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection