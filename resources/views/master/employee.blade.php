@extends('app.layout')

@section('content')

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="employeeForm">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter phone" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control" name="company" id="company" placeholder="Enter company">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" name="vehicle_number" id="vehicle_number" placeholder="Enter vehicle number">
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

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="employeeEditForm">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit-name" placeholder="Enter name" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="edit-phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" id="edit-phone" placeholder="Enter phone" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="edit-company" class="form-label">Company</label>
                                <input type="text" class="form-control" name="company" id="edit-company" placeholder="Enter company">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit-vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" name="vehicle_number" id="edit-vehicle_number" placeholder="Enter vehicle number">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Employee</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Page Title -->
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="m-0">Employee Master</h1>
            <div>
                <button type="button" id="excelExport" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i>Excel
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-plus"></i> Add Employee
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Employee Table Card -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Employee Table</h3>
            </div>
            <div class="card-body">
                <table id="employeeTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Vehicle Number</th>
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
    function togglePasswordVisibility() {
        const passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    }

    function togglePasswordFields() {
        const isChecked = document.getElementById("changePasswordCheckbox").checked;

        document.getElementById("edit-password").disabled = !isChecked;
        document.getElementById("edit-repassword").disabled = !isChecked;
        document.getElementById("edit-showPassword").disabled = !isChecked;
    }

    function togglePasswordEditVisibility() {
        const passwordField = document.getElementById("edit-password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    }

    $(document).ready( function () {

        var table = $('#employeeTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('master.getemployees')}}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data : 'name'},
                {data : 'phone'},
                {data : 'company'},
                {data : 'vehicle_number'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('submit','#employeeForm', function (e) {

            e.preventDefault();

            var formData = new FormData(this);

            console.log(formData);

            $.ajax({
                type: "POST",
                url: "{{ route('master.storeemployee') }}",
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
                        $('#employeeForm')[0].reset();

                        // Properly hide the Bootstrap modal
                        $('#addEmployeeModal').modal('hide');

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

        $('#editEmployeeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var editId = button.data('id');
            var editName = button.data('name');
            var editPhone = button.data('phone');
            var editCompany = button.data('company');
            var editVehicleNumber = button.data('vehicle_number');

            var modal = $(this);
            modal.find('#edit-id').val(editId);
            modal.find('#edit-name').val(editName);
            modal.find('#edit-phone').val(editPhone);
            modal.find('#edit-company').val(editCompany);
            modal.find('#edit-vehicle_number').val(editVehicleNumber);

        });

        $(document).on('submit', '#employeeEditForm', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('master.updateemployee') }}",
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
                        $('#employeeEditForm')[0].reset();
                        $('#editEmployeeModal').modal('hide');
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
                        url: "{{ route('master.deleteemployee') }}",
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

            const url = "{{ route('master.employeeexcelexport') }}";
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
