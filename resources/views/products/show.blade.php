@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Details</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-response">

                <table id="data_table" class="table table-bordered table-striped data-table table-hover">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <td>{{ $product->title ?? '--' }}</td>
                        </tr>

                        <tr>
                            <th>Description</th>
                            <td>{{ $product->description ?? '--' }}</td>
                        </tr>

                        <tr>
                            <th>Product Image</th>
                            {{-- <td><img height="50px" width="100px" src="{{ asset('img/'. $product->images->file_path)}}" alt="Product Image"> --}}
                            </td>
                        </tr>
                    </thead>

                </table>

                <table  class="table">
                    <thead>
                      <tr>
                        <th>SN</th>
                        <th>Variant Name</th>
                        <th>Variant </th>
                        <th>Price</th>
                        <th>Stock</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $key => $variant)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $variant->title ?? '--'}}</td>
                            <td>{{ $variant->pivot->variant }}</td>
                            <td>
                                @foreach($product->variantPrices as $key => $price)
                                    {{ number_format($price->price, 2) }}/
                                @endforeach
                            </td>
                            <td>
                                @foreach($product->variantPrices as $key => $stock)
                                    {{ number_format($price->stock, 2) }}/
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
