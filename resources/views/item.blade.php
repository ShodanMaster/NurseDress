@extends('app.layout')

@section('content')

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="itemForm">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="size" class="form-label">Size: </label>
                            <select class="form-control" required name="size" id="size">
                                <option value="" disabled selected> --Select Size-- </option>
                                @forelse ($sizes as $size)
                                    <option value="{{$size->id}}">{{$size->name}}</option>
                                @empty
                                    <option value="" disabled>No Values Exist</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color" class="form-label">Color: </label>
                            <select class="form-control" required name="color" id="color">
                                <option value="" disabled selected> --Select Color-- </option>
                                @forelse ($colors as $color)
                                    <option value="{{$color->id}}">{{$color->name}}</option>
                                @empty
                                    <option value="" disabled>No Values Exist</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="design" class="form-label">Design: </label>
                            <select class="form-control" required name="design" id="design">
                                <option value="" disabled selected> --Select Design-- </option>
                                @forelse ($designs as $design)
                                    <option value="{{$design->id}}">{{$design->name}}</option>
                                @empty
                                    <option value="" disabled>No Values Exist</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="sex" class="form-label">Sex: </label>
                        <select class="form-control" required name="sex" id="sex">
                            <option value="" disabled selected> --Select Sex-- </option>
                            <option value="male">Male</option>
                            <option value="female">FeMale</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="item" class="form-label">Item</label>
                    <input type="text" class="form-control" name="item" id="item" placeholder="Enter Item" required>
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

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="itemEditForm">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="size" class="form-label">Size: </label>
                            <select class="form-control" required name="size" id="edit-size">
                                <option value="" disabled selected> --Select Size-- </option>
                                @forelse ($sizes as $size)
                                    <option value="{{$size->id}}">{{$size->name}}</option>
                                @empty
                                    <option value="" disabled>No Sizes Exist</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color" class="form-label">Color: </label>
                            <select class="form-control" required name="color" id="edit-color">
                                <option value="" disabled selected> --Select Color-- </option>
                                @forelse ($colors as $color)
                                    <option value="{{$color->id}}">{{$color->name}}</option>
                                @empty
                                    <option value="" disabled>No Colors Exist</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="design" class="form-label">Design: </label>
                            <select class="form-control" required name="design" id="edit-design">
                                <option value="" disabled selected> --Select Design-- </option>
                                @forelse ($designs as $design)
                                    <option value="{{$design->id}}">{{$design->name}}</option>
                                @empty
                                    <option value="" disabled>No Designs Exist</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="sex" class="form-label">Sex: </label>
                        <select class="form-control" required name="sex" id="edit-sex">
                            <option value="" disabled selected> --Select Sex-- </option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="item" class="form-label">Item</label>
                    <input type="text" class="form-control" name="item" id="edit-item" placeholder="Enter item" required>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Item</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Page Title -->
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="m-0">Item Master</h1>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </div>
    </div>
</div>

<!-- Item Table Card -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Item Table</h3>
            </div>
            <div class="card-body">
                <table id="itemTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>title</th>
                            <th>sex</th>
                            <th>size</th>
                            <th>color</th>
                            <th>design</th>
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

        var table = $('#itemTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.getitems')}}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data : 'title'},
                {data : 'sex'},
                {data : 'size'},
                {data : 'color'},
                {data : 'design'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('submit','#itemForm', function (e) {

            e.preventDefault();

            var formData = new FormData(this);

            console.log(formData);

            $.ajax({
                type: "POST",
                url: "{{ route('admin.storeitem') }}",
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
                        $('#itemForm')[0].reset();

                        // Properly hide the Bootstrap modal
                        $('#addItemModal').modal('hide');

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

        $('#editItemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var editId = button.data('id');
            var editItem = button.data('item');
            var editSize = button.data('size');
            var editColor = button.data('color');
            var editDesign = button.data('design');
            var editSex = button.data('sex');

            var modal = $(this);
            modal.find('#edit-id').val(editId);
            modal.find('#edit-item').val(editItem);
            modal.find('#edit-size').val(editSize);
            modal.find('#edit-color').val(editColor);
            modal.find('#edit-design').val(editDesign);
            modal.find('#edit-sex').val(editSex);
        });


        $(document).on('submit', '#itemEditForm', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('admin.updateitem') }}",
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
                        $('#itemEditForm')[0].reset();
                        $('#editItemModal').modal('hide');
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
            var name = $(this).data('title');

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
                        url: "{{ route('admin.deleteitem') }}",
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
