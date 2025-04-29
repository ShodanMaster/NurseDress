@extends('app.layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Rejection Scan</h3>
            </div>
            <form id="qc-form">
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
@endsection

@section('scripts')
<script>
    $(document).ready(function () {

        $('#barcode').on('change', function (e) {
            e.preventDefault();
            let barcodeValue = $(this).val();
            console.log('barcode:', barcodeValue);
            
        });
    });
</script>
@endsection
