@extends('app.layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Rejection Scan</h3>
            </div>
            <form>
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control" name="barcode" id="barcode" placeholder="Enter Barcode" required autofocus>
                    </div>
                </div>

            </form>
        </div>
    </div>
</section>
<script>
    const myInput = document.getElementById("barcode");
        myInput.addEventListener("input", function() {

            console.log(this.value);

            $.ajax({
                type: "POST",
                url: "{{ route('transaction.qcstore') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "barcode": this.value
                },
                dataType: "json",
                success: function (response) {
                    log(response);
                }
            });

        });
    </script>
@endsection

@section('scripts')

@endsection
