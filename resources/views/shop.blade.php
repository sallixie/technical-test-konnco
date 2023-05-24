@extends('base')
@section('content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>Shop</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item active">Shop</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid product-wrapper">
  <div class="product-grid">
    <div class="product-wrapper-grid">
      <div class="row">
        @csrf
        @foreach ($items as $item)
        <div class="col-xl-3 col-sm-6 xl-4">
          <div class="card">
            <div class="product-box">
              <div class="product-img"><img class="img-fluid" src="{{ asset("storage/items/" . $item->gambar) }}" alt="{{ $item->nama }}">
                <div class="product-hover">
                  <ul>
                    <li><a data-bs-toggle="modal" data-bs-target="#detail-{{ $item->id }}"><i class="icon-eye"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="modal fade" id="detail-{{ $item->id }}">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <div class="product-box row">
                        <div class="product-img col-lg-6"><img class="img-fluid" src="{{ asset("storage/items/" . $item->gambar) }}" alt="{{ $item->nama }}"></div>
                        <div class="product-details col-lg-6 text-start"><a href="#">
                            <h4>{{ $item->nama }}</h4></a>
                          <div class="product-price">Rp. {{ number_format($item->harga, 2, ',', '.') }}
                            <del>Rp. {{ number_format($item->harga + 50000, 2, ',', '.') }}</del>
                          </div>
                          <div class="product-view">
                            <h6 class="f-w-600">Product Details</h6>
                            <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo.</p>
                          </div>
                          <div class="product-qnty">
                            <h6 class="f-w-600">Quantity</h6>
                            <fieldset>
                              <div class="input-group">
                                <input class="touchspin text-center quantity" type="number" value="1" max="{{ $item->stok }}">
                              </div>
                            </fieldset>
                            <div class="addcart-btn"><button class="btn btn-primary w-100 btn-add-to-cart" type="button" data-id={{ $item->id }} data-user="{{ Auth::user()->id }}">Add to Cart</button></div>
                          </div>
                        </div>
                      </div>
                      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-details"><a href="#">
                  <h4>{{ $item->nama }}</h4></a>
                <div class="product-price">Rp. {{ number_format($item->harga, 2, ',', '.') }}
                  <del>Rp. {{ number_format($item->harga + 50000, 2, ',', '.') }}</del>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid Ends-->
@endsection

@section('script')
  <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
  <script src="{{ asset('assets/js/custom/add-to-cart.js') }}"></script>
@endsection