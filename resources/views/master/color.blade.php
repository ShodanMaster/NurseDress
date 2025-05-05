@extends('app.layout')

@section('content')

<!-- Add Color Modal -->
<div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="addColorModalLabel">Add Color</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="colorForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" name="color" id="color" placeholder="Enter Color" required autofocus>
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

<!-- Edit Color Modal -->
<div class="modal fade" id="editColorModal" tabindex="-1" aria-labelledby="editColorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="editColorModalLabel">Edit Color</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="colorEditForm">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" name="color" id="edit-color" placeholder="Enter Color" required autofocus>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Color</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Page Title -->
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="m-0">Color Master</h1>
            <div>
                <button type="button" id="excelExport" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i>Excel
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addColorModal">
                    <i class="fas fa-plus"></i> Add Color
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Color Table Card -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Color Table</h3>
            </div>
            <div class="card-body">
                <table id="colorTable" class="table table-bordered table-striped">
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
    $(document).ready( function () {

        var table = $('#colorTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('master.getcolors')}}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data : 'name'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('submit','#colorForm', function (e) {

            e.preventDefault();

            var formData = new FormData(this);

            console.log(formData);

            $.ajax({
                type: "POST",
                url: "{{ route('master.storecolor') }}",
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
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });

                        // Reset form
                        $('#colorForm')[0].reset();

                        // Properly hide the Bootstrap modal
                        $('#addColorModal').modal('hide');

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
                        text: xhr.responseJSON.message || 'Something went wrong!',
                    });
                }

            });


        });

        $('#editColorModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var editId = button.data('id');
            var editTitle = button.data('name');

            var modal = $(this);
            modal.find('#edit-id').val(editId);
            modal.find('#edit-color').val(editTitle);

        });

        $(document).on('submit', '#colorEditForm', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('master.updatecolor') }}",
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
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        $('#colorEditForm')[0].reset();
                        $('#editColorModal').modal('hide');
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
                        url: "{{ route('master.deletecolor') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            table.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonText: 'OK'
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

            const url = "{{ route('master.colorexcelexport') }}";
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
