@extends('app.layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Storage Scan</h3>
            </div>
            <form>
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="grn_number" class="form-label">grnNumber ID</label>
                        <select class="form-control" name="grn_number" id="grn_number">
                            <option value="">Select GRN Number</option>
                            @foreach($grnNumbers as $grnNumber)
                                <option value="{{ $grnNumber->id }}">{{ $grnNumber->grn_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bin" class="form-label">Bin</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="bin" id="bin" placeholder="Enter Bin" required>
                            <!-- Reset Button with Icon -->
                            <button type="button" class="btn btn-outline-secondary" id="reset-bin">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
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
    const barcodeInput = document.getElementById("barcode");
        barcodeInput.addEventListener("input", function() {


            $.ajax({
                type: "POST",
                url: "{{ route('transaction.storagescan') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "barcode": this.value,
                    "bin": document.getElementById('bin').value,
                    "grn_number": document.getElementById('grn_number').value,
                },
                dataType: "json",
                success: function (response) {
                    log(response);
                }
            });

        });

    const binInput = document.getElementById("bin");
    binInput.addEventListener("input", function() {

        console.log(this.value);

        $.ajax({
            type: "GET",
            url: "{{ route('transaction.fetchbin') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "bin": this.value
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if(response.status == 200){
                    $('#bin').attr('readonly', true);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Bin',
                        text: response.message,
                    });
                    $('#bin').attr('readonly', false);
                    $('#bin').val('');
                }
            }
        });

    });

    document.getElementById('reset-bin').addEventListener('click', function () {
        const binInput = document.getElementById('bin');

        binInput.removeAttribute('readonly');
        binInput.value = '';
    });

</script>
@endsection

@section('scripts')

@endsection
