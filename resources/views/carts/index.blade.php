@extends('layouts.app')
 
@section('content')
<div class="container d-flex justify-content-center mt-3">
    <div class="w-75">
        <h1>ショッピングカート</h1>

        <div class="row">
            <div class="offset-8 col-4">
                <div class="row">
                    <div class="col-6">
                        <h2>数量</h2>
                    </div>
                    <div class="col-6">
                        <h2>合計</h2>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="container">
            <div class="row">
                @foreach ($cart as $product)
                <div class="col-md-12 mb-4">
                    <div class="card p-3">
                        <div class="row align-items-center">
                            <!-- 商品画像 -->
                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <a href="{{ route('products.show', $product->id) }}">
                                    @if ($product->options->image)
                                    <img src="{{ asset($product->options->image) }}" class="img-fluid img-thumbnail w-100">
                                    @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-fluid img-thumbnail w-100">
                                    @endif
                                </a>
                            </div>
        
                            <!-- 商品名 -->
                            <div class="col-md-4">
                                <h4 class="mb-0">{{ $product->name }}</h4>
                            </div>
        
                            <!-- 削除ボタン -->
                            <div class="col-md-2 text-center">
                                <form action="{{ route('cart.remove', $product->rowId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </div>
        
                            <!-- 数量 -->
                            <div class="col-md-2 text-center">
                                <span>数量: {{ $product->qty }}</span>
                            </div>
        
                            <!-- 合計金額 -->
                            <div class="col-md-2 text-center">
                                <span>￥{{ number_format($product->qty * $product->price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
         
        <hr>
 
        <div class="offset-8 col-4">
            <div class="row">
                <div class="col-6">
                    <h2>送料</h2>
                </div>
                <div class="col-6">
                    <h2>￥{{ $carriage_cost }}</h2>
                </div>
            </div>
        </div>

        <hr>

        <div class="offset-8 col-4">
            <div class="row">
                <div class="col-6">
                    <h2>合計</h2>
                </div>
                <div class="col-6">
                    <h2>￥{{$total}}</h2>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    表示価格は税込みです
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <a href="{{route('top')}}" class="btn samuraimart-favorite-button border-dark text-dark mr-3">
                買い物を続ける
            </a>
            @if ($total > 0)
            <a href="{{ route('checkout.index') }}" class="btn samuraimart-submit-button">購入に進む</a>
            @else
            <button class="btn samuraimart-submit-button disabled">購入に進む</button>
            @endif
            <div class="modal fade" id="buy-confirm-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">購入を確定しますか？</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="閉じる">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn samuraimart-favorite-button border-dark text-dark" data-bs-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn samuraimart-submit-button">購入</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection