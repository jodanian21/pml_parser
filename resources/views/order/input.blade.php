@extends('layouts.default')

@section('content')
<div class="row justify-content-md-center">
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
                if (
                    typeof err.response != "undefined"
                    && err.response.status === 422
                ) {
                    toggleStatus(
                        true,
                        err.response.data.message
                    );
                }

                console.log(err)
            })
    }
    </script>
@endpush