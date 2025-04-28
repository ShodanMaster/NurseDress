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
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-control" name="type" id="type" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Employee</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePasswordVisibility()">
                                <label class="form-check-label" for="showPassword">
                                    Show Password
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="repassword" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="repassword" placeholder="Comfirm password" required>
                            </div>
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
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="edit-username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="label">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-control" name="type" id="edit-type" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="admin">Admin</option>
                                    <option value="employee">Employee</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="changePasswordCheckbox" onclick="togglePasswordFields()">
                            <label class="form-check-label" for="changePasswordCheckbox">
                                Change Password
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit-password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="edit-password" placeholder="Enter password" disabled>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="edit-showPassword" onclick="togglePasswordEditVisibility()" disabled>
                                <label class="form-check-label" for="edit-showPassword">
                                    Show Password
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit-repassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="edit-repassword" placeholder="Confirm password" disabled>
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
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fas fa-plus"></i> Add Employee
            </button>
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
                            <th>User Name</th>
                            <th>User Type</th>
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
            ajax: "{{route('admin.getemployees')}}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data : 'name'},
                {data : 'type'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('submit','#employeeForm', function (e) {

            e.preventDefault();

            var formData = new FormData(this);

            console.log(formData);

            $.ajax({
                type: "POST",
                url: "{{ route('admin.storeemployee') }}",
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
            var editTitle = button.data('username');
            var editType = button.data('type');

            var modal = $(this);
            modal.find('#edit-id').val(editId);
            modal.find('#edit-username').val(editTitle);
            modal.find('#edit-type').val(editType);

        });

        $(document).on('submit', '#employeeEditForm', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateemployee') }}",
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
                        url: "{{ route('admin.deleteemployee') }}",
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
    });

</script>
@endsection
