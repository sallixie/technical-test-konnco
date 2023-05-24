@extends('base')
@section('content')

<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <div class="page-header-left">
          <h3>Cart</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item active">Cart</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Cart</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="order-history table-responsive wishlist">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Nama Item</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($cart->cartItems as $cartItem)
                  <tr>
                    <td><img class="img-fluid img-40" src="{{ asset("storage/items/" . $cartItem->item->gambar) }}" alt="{{ $cartItem->item->nama }}"></td>
                    <td>
                      <div class="product-name"><h6>{{ $cartItem->item->nama }}</h6></div>
                    </td>
                    <td>Rp. {{ number_format($cartItem->item->harga, 2, ',', '.') }}</td>
                    <td>
                      {{ $cartItem->jumlah_item }}
                    </td>
                    <td><i data-feather="x-circle"></i></td>
                    <td>Rp. {{ number_format($cartItem->total_harga, 2, ',', '.') }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection