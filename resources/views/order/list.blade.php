@extends('layouts.default')

@section('content')

@include('component.sidebar', ['toppings' => $toppings])

<span style="font-size:16px;cursor:pointer" id="myMenu" class="btn btn-success" onclick="openToppings()">&#9776; View Toppings</span>

<div class="mt-5">
    <h3><strong>Order List</strong></h3>
    <hr>
    <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th> Pizza Details </th>
          </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td> 
                        <h5>Order {{ $order->number }}</h5>
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
                        <hr>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
      </table>
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