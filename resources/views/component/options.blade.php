@php
use App\Constants\Size;
@endphp

<div id="options" class="sidenav">

    <div>
        <h5 class="text-white text-center">Size List: <h5>
        @foreach (Size::all as $item)
        <a href="#">{{ $item }}</a>
        @endforeach
    </div>

    <div class="m-3">
        <h5 class="text-white text-center">Types: <h5>
        @foreach ($types as $item)
        <a href="#">{{ $item->name }}</a>
        @endforeach
    </div>

    <div class="m-3">
        <h5 class="text-white text-center">Crust List: <h5>
        @foreach ($crusts as $item)
        <a href="#">{{ $item->name }}</a>
        @endforeach
    </div>

    <div class="m-3">
        <h5 class="text-white text-center">Toppings List: <h5>
        @foreach ($toppings as $item)
        <a href="#">{{ $item->name }}</a>
        @endforeach
    </div>
</div>