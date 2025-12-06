<!-- Shop Sidebar Start -->
@include('front.products.filters')
<!-- Shop Sidebar End -->

<!-- Shop Product Start -->
<div class="col-lg-9 col-md-12">
    <div class="row pb-3">
        <div class="col-12 pb-1">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="dropdown ml-4">
                    <button class="btn border dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        Sort by
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                        <a class="dropdown-item" href="#">Latest</a>
                        <a class="dropdown-item" href="#">Lowest Price</a>
                        <a class="dropdown-item" href="#">Highest Price</a>
                        <a class="dropdown-item" href="#">Best Selling</a>
                        <a class="dropdown-item" href="#">Featured</a>
                    </div>
                </div>
            </div>
        </div>
        @foreach($categoryProducts as $product)
            @php
                $fallbackImage = asset('front/images/products/no-image.png');
                $image = '';
                if (!empty($product['main_image'])) {
                    $image = asset('product-image/medium/' . $product['main_image']);
                } elseif (!empty($product['product_images'][0]['image'])) {
                    $image = asset('product-image/medium/' . $product['product_images'][0]['image']);
                } else {
                    $image = $fallbackImage;
                }
            @endphp
            <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                <div class="card product-item border-0 mb-4">
                    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <a href="#">
                            <img class="img-fluid w-100" src="{{ $image }}" alt="{{ $product['product_name'] }}">
                        </a>
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3">{{ $product['product_name'] }}</h6>
                        <div class="d-flex justify-content-center">
                            <h6>{{ $product['final_price'] }}</h6>
                            @if($product['product_discount'] > 0)
                                <h6 class="text-muted ml-2"><del>{{ $product['product_price'] }}</del></h6>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border">
                        <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Просмотреть детали</a>
                        <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Добавить в корзину</a>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-12 pb-1">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-3">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- Shop Product End -->
