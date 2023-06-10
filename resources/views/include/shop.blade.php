<div class="row">

    @forelse ($product as $row)
        <div class="col-lg-4 col-md-6 col-12">
            <div class="single-product colstyle">
                <div class="product-img">
                    <a href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">
                        <img class="default-img"
                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                            alt="{{ $row->product }}">
                        <img class="hover-img"
                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                            alt="{{ $row->product }}">
                        @if ($row->in_stock == 0 || $row->in_stock <= 0)
                            <span class="out-of-stock">Out of stock</span>
                        @endif
                    </a>
                    <div class="button-head">
                        <div class="product-action">
                            <a class="viewProduct" title="Quick View" style=" height: 29px;"
                                href="{{ route('product.detail', Crypt::encrypt($row->id)) }}"><i
                                    class=" ti-eye"></i><span>Quick Shop</span></a>
                        </div>
                        <div class="product-action-2">
                            @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                <a href="javascript:void(0)">Out of stock</a>
                            @else
                                <a title="Add to cart" href="javascript:void(0)" id="addToCart"
                                    data-productid="{{ $row->id }}">Add to cart</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="product-content">
                    <h3><a href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">{{ $row->product }}</a>
                    </h3>
                    <div class="product-price">
                        <span>PKR {{ $row->price }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-lg-12 col-md-12 col-12 colstyle">
            <div class="text-center">
                <h4>No Record Found!</h4>
            </div>
        </div>
    @endforelse
    <div class="col-lg-12 col-md-12 col-12">
        <div id="paginationLinks" style="display: flex;">
            {{ $product->links() }}
        </div>
    </div>

</div>
