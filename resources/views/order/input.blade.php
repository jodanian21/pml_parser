@extends('layouts.default')

@section('content')

@include('component.options', compact(
    'toppings',
    'crusts',
    'types'
))

<span style="cursor:pointer" id="myMenu" class="btn btn-success" onclick="openToppings()">&#9776; Pizza Options</span>
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#sampleFormat">
    Sample Format
  </button>

<div class="modal fade" id="sampleFormat" tabindex="-1" role="dialog" aria-labelledby="sampleFormatLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="sampleFormatLabel">Sample PML Format</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
    <pre class="text-muted">
{order number="123"}
    {pizza number="1"}
        {size}large{/size}
        {crust}hand-tossed{/crust}
        {type}custom{/type}
        {toppings area="0"}
            {item}pepperoni{/item}
            {item}extra cheese{/item}
        {/toppings}
        {toppings area="1"}
            {item}sausage{/item}
        {/toppings}
        {toppings area="0"}
            {item}mushrooms{/item}
        {/toppings}
    {/pizza}
    {pizza number="2"}
        {size}medium{/size}
        {crust}deep dish{/crust}
        {type}pepperoni{/type}
    {/pizza}
{/order}
    </pre>
    </div>
    </div>
</div>
</div>

<div class="row justify-content-md-center mt-2">
    <div class="col col-md-5">
        <div class="card">
            <div class="card-header">
                <h1>Orders</h1>
                <div id="status" class="alert alert-danger" role="alert" style="display: none">
                </div>
                <div id="success" class="alert alert-success" role="alert" style="display: none">
                </div>
            </div>
            <div class="card-body">
                <form id="textEditor">
                    <div>
                        <textarea name="orderString" id="code"></textarea>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <label class="btn btn-primary btn-block">
                    <i class="fa fa-save"></i> Save <input type="submit" form="textEditor" hidden>
                </label>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/order/input.js') }}"></script>
    <script>
        var store = localStorage.getItem('status');

        const toggleSuccess = (show, msg = "") => {
            let success = document.querySelector("#success");

            if (show == true) {
                success.style.display = "block";
            } else {
                success.style.display = "none";
            }

            success.innerText = msg;
        }


        if (store) {
            localStorage.removeItem('status')
            toggleSuccess(true, store)
        }
        const form = document.querySelector("#textEditor");
        const toggleStatus = (show, msg = "") => {
            let status = document.querySelector("#status");

            if (show == true) {
                status.style.display = "block";
            } else {
                status.style.display = "none";
            }

            status.innerText = msg;
        }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitOrder();
    });

    const submitOrder = () => {
        toggleStatus(false);
        toggleSuccess(false);
        window.axios.post("{!! route('order_api'); !!}", {
                orderString: document.getElementById('code').value || ""
            }).then(res => {
                var { data } =  res.data
                localStorage.setItem("status", "Successfully Saved Order!");
                location.reload();
            }).catch(err => {
                var msg = "Something Went wrong!"
                if (
                    typeof err.response != "undefined"
                    && err.response.status === 422
                ) {
                    msg = err.response.data.message
                }
                
                toggleStatus(true, msg);
                console.log(err)
            })
    }
    </script>
@endpush

@push('scripts')
    <script>
        function openToppings() {
            document.getElementById("options").style.width = "50vw";
        }

        window.addEventListener('click', function(e) {
            if (!document.getElementById('options').contains(e.target) && !document.getElementById('myMenu').contains(e.target)){
                document.getElementById("options").style.width = "0px";
            }
        });
    </script>
@endpush