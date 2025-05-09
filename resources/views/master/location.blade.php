@extends('app.layout')

@section('content')

<!-- Add Location Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="addLocationModalLabel">Add Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="locationForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" name="location" id="location" placeholder="Enter location" autofocus>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="excelLocation" class="form-label">Excel Upload</label>
                            <input type="file" class="form-control" name="excelLocation" id="excelLocation" placeholder="Excel Upload">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="form-label">Click Here.</label>
                            {{-- <label class="form-label">Click to Download Excel Template</label><br> --}}
                            <a href="{{asset('excelTemplates/excel_template.xlsx')}}"><button type="button" class="btn btn-success">
                                <i class="fas fa-file-excel mr-1"></i>Download Template
                            </button></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Edit Location Modal -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="editLocationModalLabel">Edit Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="locationEditForm">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" name="location" id="edit-location" placeholder="Enter location" required autofocus>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Location</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Page Title -->
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="m-0">Location Master</h1>
            <div>
                <button type="button" id="excelExport" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i>Excel
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                    <i class="fas fa-plus"></i> Add Location
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Location Table Card -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Location Table</h3>
            </div>
            <div class="card-body">
                <table id="locationTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Your dynamic rows here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script>
    document.getElementById('excelLocation').addEventListener('change', function(){
        const fileInput = this;
        constLocationput = document.getElementById('location');

        if (fileInput.files.length > 0) {

            sizeInput.value = '';
            sizeInput.disabled = true;
        } else {

            sizeInput.disabled = false;
        }
    });

    $(document).ready( function () {

        var table = $('#locationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('master.getlocations')}}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data : 'name'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('submit','#locationForm', function (e) {

            e.preventDefault();

            console.log('qwerty');

            var formData = new FormData(this);

            console.log(formData);

            $.ajax({
                type: "POST",
                url: "{{ route('master.storelocation') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status === 200) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        // Reset form
                        $('#locationForm')[0].reset();
                        $('#location').prop('disabled', false);

                        // Properly hide the Bootstrap modal
                        $('#addLocationModal').modal('hide');

                    } else {
                        $('#location').prop('disabled', false);
                        $('#excelLocation').val('');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                },
                error: function (xhr) {
                    $('#location').prop('disabled', false);
                    $('#excelLocation').val('');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }

            });

        });

        $('#addLocationModal').on('hidden.bs.modal', function () {
            $('#locationForm')[0].reset();
            $('#location').prop('disabled', false);
        });

        $('#editLocationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var editId = button.data('id');
            var editTitle = button.data('name');

            var modal = $(this);
            modal.find('#edit-id').val(editId);
            modal.find('#edit-location').val(editTitle);

        });

        $(document).on('submit', '#locationEditForm', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('master.updatelocation') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === 200) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        $('#locationEditForm')[0].reset();
                        $('#editLocationModal').modal('hide');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong!',
                    });
                }
            });
        });


        $(document).on('click', '.deleteButton', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure delete ' + name + ' ?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData();
                    formData.append('id', id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('master.deletelocation') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            table.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });
        });

        $(document).on('click', '#excelExport', function (e) {
            e.preventDefault();

            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Exporting...');

            const url = "{{ route('master.locationexcelexport') }}";
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', '');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            setTimeout(() => {
                $btn.prop('disabled', false).html('<i class="fas fa-file-excel mr-1"></i>Excel');
            }, 5000);
        });
    });

</script>
@endsection
