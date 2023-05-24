@extends('base')
@section('content')

<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-sm-6">
        <h3>Payment</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item">Checkout</li>
          <li class="breadcrumb-item active">Payment</li>
        </ol>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container invoice">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">                            
            <div>
              <div>
                <div class="row invo-header text-center">
                  <div class="col-md-12 col-sm-12">
                    <div class="badge badge-warning">Pending</div>
                  </div>
                  <h5>{{ strtoupper($midtrans["va_numbers"][0]["bank"]) }} Virtual Account Billing</h5>
                  <h2>{{ $midtrans["va_numbers"][0]["va_number"] }}</h2>
                  <small class="text-secondary">Expire pada {{ 
                    date('d F Y H:i:s', strtotime($midtrans["expiry_time"]))
                  }}</small>
                </div>
              </div>
              <!-- End InvoiceTop-->
              <div class="row invo-profile">
                <div class="col-xl-4">
                  <div class="media">
                    <div class="media-body m-l-20">
                      <h4 class="media-heading f-w-600">{{ Auth::user()->nama }}</h4>
                      <p>{{ Auth::user()->email }}<br><span class="digits">{{ Auth::user()->telepon }}</span></p>
                    </div>
                  </div>
                </div>
                <div class="col-xl-8">
                  <div class="text-xl-end" id="project">
                    <h6>Alamat</h6>
                    <p>{{ Auth::user()->alamat }}</p>
                  </div>
                </div>
              </div>
              <!-- End Invoice Mid-->
              <div>
                <div class="table-responsive invoice-table" id="table">
                  <table class="table table-bordered table-striped">
                    <tbody>
                      <tr>
                        <td class="item">
                          <h6 class="p-2 mb-0">Nama Barang</h6>
                        </td>
                        <td class="Hours">
                          <h6 class="p-2 mb-0">Jumlah</h6>
                        </td>
                        <td class="Rate">
                          <h6 class="p-2 mb-0">Harga</h6>
                        </td>
                        <td class="subtotal">
                          <h6 class="p-2 mb-0">Total Harga</h6>
                        </td>
                      </tr>
                      @foreach ($cart->cartItems as $cartItem)
                      <tr>
                        <td>
                          <label>{{ $cartItem->item->nama }}</label>
                        </td>
                        <td>
                          <p class="itemtext digits">{{ $cartItem->jumlah_item }}</p>
                        </td>
                        <td>
                          <p class="itemtext digits">Rp. {{ number_format($cartItem->item->harga, 2, ',', '.') }}</p>
                        </td>
                        <td>
                          <p class="itemtext digits">Rp. {{ number_format($cartItem->total_harga, 2, ',', '.') }}</p>
                        </td>
                      </tr>
                      @endforeach
                      <tr>
                        <td></td>
                        <td></td>
                        <td class="Rate">
                          <h6 class="mb-0 p-2">Total</h6>
                        </td>
                        <td class="payment digits">
                          <h6 class="mb-0 p-2">Rp. {{ number_format($cart->total_biaya, 2, ',', '.') }}</h6>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- End Table-->
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div>
                      <p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; please process this invoice within that time. There will be a 5% interest charge per month on late invoices.</p>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End InvoiceBot-->
            </div>
            <!-- End Invoice-->
            <!-- End Invoice Holder-->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

@endsection