@php
use App\Constants\Size;
@endphp

@extends('layouts.default')

@section('content')

@include('component.sidebar', ['toppings' => $toppings])

<span style="font-size:16px;cursor:pointer" id="myMenu" class="btn btn-success" onclick="openToppings()">&#9776; View Toppings</span>

<div class="mt-5">
    <h3><strong>Order List</strong></h3>
    <hr>
    <p>
        <a class="btn btn-success" data-toggle="collapse" href="#filterBox" role="button" aria-expanded="false" aria-controls="filterBox">
            <i class="fa fa-filter"></i> Filter
        </a>
    </p>
    <div class="collapse show" id="filterBox">
        <div class="card card-body">
            <form>
                @php
                    $query = request()->query();
                @endphp
                <div class="form-group row">
                    <div class="col-sm-2 m-1">
                        <select name="size" class="form-control">
                            <option value="" disabled {{ empty($query['size']) ? 'selected' : '' }}>Size</option>
                            @foreach (Size::all as $size)
                            <option {{ !empty($query['size']) && $query['size'] == $size ? 'selected' : '' }}> {{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-2 m-1">
                        <select name="crust" class="form-control">
                            <option value="" disabled {{ empty($query['crust']) ? 'selected' : '' }}>Crust</option>
                            @foreach ($crusts as $crust)
                                <option {{ !empty($query['crust']) && $query['crust'] == $crust->name ? 'selected' : '' }} value="{{ $crust->name }}"> {{ $crust->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-3 m-1">
                        <select name="type" class="form-control" value="{{ request()->query()['size'] ?? '' }}">
                            <option value="" disabled {{ empty($query['type']) ? 'selected' : '' }}>Type</option>
                            @foreach ($types as $type)
                                <option {{ !empty($query['type']) && $query['type'] == $type->name ? 'selected' : '' }} value="{{ $type->name }}"> {{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-2 m-1">
                        <input type="number" name="toppings" class="form-control" min="0" max="12" placeholder="No of Toppings (upto)" value="{{ request()->query()['toppings'] ?? '' }}">
                    </div>

                    <div class="col-sm-2 m-1">
                        <a href="{{ route('list') }}" class="btn btn-secondary">Reset</a>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
      </div>
    </div>

    <div class="col col-md-12 mt-2">
        <div class="row justify-content-md-center">

            @foreach ($orders as $order)
                <div class="card text-white m-3 bg-dark" style="max-width: 18rem;">
                    <h4 class="card-header bg-success">Order # {{ $order->number }}</h4>
                    <div class="card-body">
                        @foreach ($order->pizzas as $pizza)
                            <strong>Pizza {{ $pizza->number }} </strong>-
                            {{ $pizza->size }},
                            {{ $pizza->crust }},
                            {{ $pizza->type }} <br>

                            <div class="ml-5">
                            <!--- Custom Types Only ---->
                            @if ($pizza->type == "custom")
                                <!--- Whole ---->
                                @php
                                    $whole = $order->topping_details->filter(function ($value) use ($pizza) {
                                        return $value->area == 0 && $value->pizza_id == $pizza->id;
                                    });
                                @endphp

                                @if (!$whole->isEmpty())
                                    Toppings Whole: <br>
                                @endif

                                @foreach ($whole as $toppings)
                                    @if ($toppings->pizza_id == $pizza->id)
                                        <div class="ml-5">{{ ucwords($toppings->name) }}</div>
                                    @endif
                                @endforeach

                                <!--- First Half ---->
                                @php
                                    $firstHalf = $order->topping_details->filter(function ($value) use ($pizza) {
                                        return $value->area == 1 && $value->pizza_id == $pizza->id;
                                    });
                                @endphp

                                @if (!$firstHalf->isEmpty())
                                    Toppings First-Half: <br>
                                @endif

                                @foreach ($firstHalf as $toppings)
                                    @if ($toppings->pizza_id == $pizza->id)
                                        <div class="ml-5">{{ ucwords($toppings->name) }}</div>
                                    @endif
                                @endforeach

                                <!--- Second Half ---->
                                @php
                                    $secondHalf = $order->topping_details->filter(function ($value) use ($pizza) {
                                        return $value->area == 2 && $value->pizza_id == $pizza->id;
                                    });
                                @endphp

                                @if (!$secondHalf->isEmpty())
                                    Toppings First-Half: <br>
                                @endif

                                @foreach ($secondHalf as $toppings)
                                    @if ($toppings->pizza_id == $pizza->id)
                                    <div class="ml-5">{{ ucwords($toppings->name) }}</div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>
      {{ $orders->links('component.pagination') }}
</div>

@endsection

@push('scripts')
    <script>
        function openToppings() {
            document.getElementById("mySidenav").style.width = "50vw";
        }

        window.addEventListener('click', function(e) {
            if (!document.getElementById('mySidenav').contains(e.target) && !document.getElementById('myMenu').contains(e.target)){
                document.getElementById("mySidenav").style.width = "0px";
            }
        });
    </script>
@endpush