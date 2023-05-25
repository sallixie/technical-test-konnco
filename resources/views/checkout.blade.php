@extends('base')
@section('content')

<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <div class="page-header-left">
          <h3>Checkout</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item">Cart</li>
            <li class="breadcrumb-item active">Checkout</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
  <div class="card">
    <div class="card-header pb-0">
      <h5>Detail Pembayaran</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-xl-6 col-sm-12">
            <div class="row">
              <div class="mb-3 col-sm-12">
                <label for="inputEmail4">Nama</label>
                <h6>{{ Auth::user()->nama }}</h6>
              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-sm-6">
                <label for="inputEmail5">Telepon</label>
                <h6>{{ Auth::user()->telepon }}</h6>
              </div>
              <div class="mb-3 col-sm-6">
                <label for="inputPassword7">Email</label>
                <h6>{{ Auth::user()->email }}</h6>
              </div>
            </div>
            <div class="mb-3">
              <label for="inputAddress5">Alamat</label>
              <h6>{{ Auth::user()->alamat }}</h6>
            </div>
        </div>
        <div class="col-xl-6 col-sm-12">
          <div class="checkout-details">
            <div class="order-box">
              <div class="title-box">
                <div class="checkbox-title">
                  <h4 class="mb-0">Barang </h4><span>Total</span>
                </div>
              </div>
              <ul class="qty">
                @foreach ($cart->cartItems as $cartItem)
                  <li>{{ $cartItem->item->nama }} × {{ $cartItem->jumlah_item }} <span>Rp. {{ number_format($cartItem->total_harga, 2, ',', '.') }}</span></li>
                @endforeach
              </ul>
              <ul class="sub-total total">
                <li>Total <span class="count">Rp. {{ number_format($cart->total_biaya, 2, ',', '.') }}</span></li>
              </ul>
              <div class="animate-chk">
                <h4>Pilih Metode Pembayaran</h4>
                <form action="/checkout/payment" method="POST" id="form-checkout">
                @csrf
                <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                  <div class="row">
                    <div class="col">
                      <label class="d-block" for="bank1">
                        <input class="radio_animated" id="bank1" type="radio" name="bank" checked value="bca">BCA Virtual Account
                      </label>
                      <label class="d-block" for="bank2">
                        <input class="radio_animated" id="bank2" type="radio" name="bank" value="bni">BNI Virtual Account 
                      </label>
                      <label class="d-block" for="bank3">
                        <input class="radio_animated" id="bank3" type="radio" name="bank" value="bri">BRI Virtual Account 
                      </label>
                      <label class="d-block" for="bank4">
                        <input class="radio_animated" id="bank4" type="radio" name="bank" value="echannel">Mandiri Bill Payment
                      </label>
                      <label class="d-block" for="bank5">
                        {{-- <input class="radio_animated" id="bank5" type="radio" name="bank" value="permata" disabled>Permata Virtual Account --}}
                      </label>
                    </div>
                  </div>
                </div>
                <div class="order-place mt-3"><button type="button" class="btn btn-primary" id="btn-checkout">Checkout</button></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/custom/checkout.js') }}"></script>

@if(session("error"))
<script>
$(document).ready(function () {
  swal("Error", "{{ session('error') }}", "error");
});
</script>
@endif

@endsection