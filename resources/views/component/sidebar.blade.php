<div id="mySidenav" class="sidenav">
    <h5 class="text-white text-center">Toppings List: <h5>
    @foreach ($toppings as $item)
    <a href="#">{{ ucwords($item->name) }} <span class="badge badge-success">{{ $item->total }}</span></a>
    @endforeach
</div>