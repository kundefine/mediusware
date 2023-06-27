@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{url()->current()}}" method="get" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="{{request()->query('title') ?? null}}">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option value="">Please Select One</option>
                        @foreach($variants as $variant)
                            <optgroup label="{{$variant->title}}">
                                @foreach($variant->pdv()->get() as $pdv)
                                    <option value="{{$variant->id}},{{$pdv->variant}}" @if(($variant->id . ',' . $pdv->variant) == request()->query('variant')) selected @endif>{{$pdv->variant}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control" value="{{request()->query('price_from') ?? null}}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control" value="{{request()->query('price_to') ?? null}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{request()->query('date') ?? null}}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right mx-1"><i class="fa fa-search"></i></button>
                    <a href="{{url()->current()}}" class="btn btn-danger float-right"><i class="fa fa-times"></i></a>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td style="width: 250px">{{$product->title}} <br> Created at : {{$product->created_at->format("d-M-y")}}</td>
                            <td style="width: 500px">
                                <p style="height: 80px; overflow: hidden" id="des-{{$product->id}}">
                                    {{$product->description}}
                                </p>
                                <button onclick="$('#des-{{$product->id}}').toggleClass('h-auto')"
                                        class="btn btn-sm btn-link">Show more
                                </button>
                            </td>
                            <td>
                                <dl class="row mb-0" style="height: 80px; overflow: hidden"
                                    id="variant-{{$product->id}}">
                                    @foreach($product->prices as $price)
                                        <dt class="col-sm-3 pb-0">
                                            {{$price->pv1 ? $price->pv1->variant . '/ ' : ''}}{{$price->pv2 ? $price->pv2->variant . '/' : ''}} {{$price->pv3 ? $price->pv3->variant : ''}}
                                        </dt>
                                        <dd class="col-sm-9">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 pb-0">Price
                                                    : {{ number_format($price->price,2) }}</dt>
                                                <dd class="col-sm-8 pb-0">InStock
                                                    : {{ number_format($price->stock,2) }}</dd>
                                            </dl>
                                        </dd>
                                    @endforeach
                                </dl>


                                <button onclick="$('#variant-{{$product->id}}').toggleClass('h-auto')"
                                        class="btn btn-sm btn-link">Show more
                                </button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    @php
                        $showingForm = $products->currentPage() * $products->perPage() - $products->perPage() + 1;
                        $totalShowing = $products->currentPage() * $products->perPage();
                        if($totalShowing > $products->total()) $totalShowing = $products->total();
                    @endphp
                    <p>Showing {{$showingForm}} to {{$totalShowing}} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-2">
                    {{$products->links()}}
                </div>
            </div>
        </div>
    </div>

@endsection
