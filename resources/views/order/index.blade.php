@extends('layouts.default')

@section('content')
<div class="row justify-content-md-center">
    <div class="col col-md-5" id="content">
        <div class="card">
            <div class="card-header">
                <form id="uploadFile">
                    <label class="btn btn-primary btn-block" id="btnBrowse">
                        <i class="fa fa-upload"></i> Browse <input id="inputFile" type="file" hidden>
                    </label>
                    <button type="submit" id="btnSave" class="btn btn-success btn-block" style="display:none">
                        <i class="fa fa-save"></i> Save
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="text-center" id="filesUploaded" style="display:none">
                    <i class="fa fa-file-code-o"></i> 
                    <span id="filename"></span>
                    <span>
                        <button type="reset" form="uploadFile" class="btn btn-default fa fa-close" onclick="fileRemove()"></button>
                    </span>
                </div>
            </div>
            <div class="card-footer">
                <div id="status" class="alert alert-danger" role="alert" style="display: none">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const input = document.querySelector("#inputFile");
    const form = document.querySelector("#uploadFile");
    var file = null;
    if (input) {
        input.addEventListener('change', function (e) {
            let message = document.querySelector("#filesUploaded");
            var fileName = e.target.files[0].name;

            let name = document.querySelector("#filename");
            message.style.display = "block";
            name.innerText  = fileName;
            toggleSave(true);
            toggleStatus(false);
            getFileContent(e.target.files[0])
        });
    }

    const toggleSave = (show) => {
        let save = document.querySelector("#btnSave");
        let browse = document.querySelector("#btnBrowse");

        if (show == true) {
            save.style.display = "block";
            browse.style.display = "none"
        } else {
            save.style.display = "none";
            browse.style.display = "block"
        }
    }

    const fileRemove = () => {
        let message = document.querySelector("#filesUploaded");
        message.style.display = "none";
        toggleSave(false);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitFile();
    });

    const toggleStatus = (show, msg = "") => {
        let status = document.querySelector("#status");

        if (show == true) {
            status.style.display = "block";
        } else {
            status.style.display = "none";
        }

        status.innerText = msg;
    }

    const getFileContent = (f) => {
        if (f.type == "text/plain") {
            let fr = new FileReader();
            fr.onload = function() {
                file = fr.result;
            }
            fr.readAsText(f);
        } else {
            alert('wrong file type!');
            form.reset();
            fileRemove();
        }
    }

    const renderData = (data) => {
        let template = `
            <div id="order" class="card mt-3">
                <div class="card-body">
                    <h5>Order: ${data.number}</h5>`;

        for (let pizza of data.pizza) {
            template += `
                Pizza ${pizza.number} - ${pizza.size} ${pizza.crust} ${pizza.type} <br>
            `;

            for(let toppings of pizza.toppings) {
                switch (toppings.area) {
                    case 0:
                        template += `&nbsp;&nbsp;&nbsp;&nbsp;Toppings Whole <br>`;
                        break;
                    case 1:
                        template += `&nbsp;&nbsp;&nbsp;&nbsp;Toppings First-Half <br>`;
                        break;
                    case 2:
                        template += `&nbsp;&nbsp;&nbsp;&nbsp;Toppings Second-Half <br>`;
                        break;
                }

                for (let items of toppings.items) {
                    template += `&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;${items} <br>`;
                }
            }
        }

        template += `</div>
            </div>
        `;
        let content = document.getElementById("content");
        content.insertAdjacentHTML('beforeend', template);
    }

    const submitFile = () => {
        window.axios.post("{!! route('order_api'); !!}", {
                orderString: file
            }).then(res => {
                fileRemove();
                var { data } =  res.data
                renderData(data);
                form.reset();
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