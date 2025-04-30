@extends('app.layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Rejection Scan</h3>
            </div>
            <form method="POST" action="{{ route('transaction.qcstore') }}" id="grn-form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grn-number" class="form-label">GRN Number</label>
                                <select class="form-control" name="grnnumber" id="grn-number" required>
                                    <option value="" selected disabled>--Select GRN Number--</option>
                                    @forelse ($grnNumbers as $grnNumber)
                                        <option value="{{ $grnNumber->id }}">{{ $grnNumber->grn_no }}</option>
                                    @empty
                                        <option value="" disabled>No GRN Numbers</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" id="footer" style="display: none;">
                    <div class="table-responsive">
                        <table class="table" id="grngrid">
                            <thead>
                                <tr>
                                    <th>Sl.no</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Accepted</th>
                                    <th>Rejected</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="qualityBody"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    $(document).ready(function () {

        $(document).on('change', '#grn-number', function () {
            var grnNumber = $(this).val();
            $.ajax({
                url: "{{ route('transaction.fetchgrn') }}",
                type: "GET",
                data: { grn_number: grnNumber },
                success: function (response) {
                    if (response.status === 200) {
                        $('#grnDetails').show();
                        $('#footer').show();
                        const data = response.data;

                        const gridBody = $('#qualityBody');
                        gridBody.empty();

                        let rowCount = 0;

                        data.grn_subs.forEach((sub, index) => {
                            rowCount++;
                            const quantity = sub.quantity || 0;

                            const newRow = `
                                <tr>
                                    <td>${rowCount}</td>
                                    <td>${sub.item_name}<input type="hidden" name="items[${rowCount}][item_id]" value="${sub.item_id}"></td>
                                    <td>${quantity}<input type="hidden" name="items[${rowCount}][quantity]" value="${quantity}" class="quantity"></td>
                                    <td><input class="form-control accepted" type="number" name="items[${rowCount}][accepted]" min="0" required></td>
                                    <td><input class="form-control rejected" type="number" name="items[${rowCount}][rejected]" min="0" required></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                </tr>
                            `;
                            gridBody.append(newRow);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching GRN details:", error);
                }
            });
        });

        // Validation on input change
        $(document).on('input', '.accepted, .rejected', function () {
            const row = $(this).closest('tr');
            const quantity = parseInt(row.find('.quantity').val()) || 0;
            const accepted = parseInt(row.find('.accepted').val()) || 0;
            const rejected = parseInt(row.find('.rejected').val()) || 0;

            if ((accepted + rejected) > quantity) {
                alert('Accepted + Rejected cannot be greater than Quantity');
                $(this).val('');
            }
        });

        // Optional: Validate on form submission too
        $('#grn-form').on('submit', function (e) {
            let valid = true;
            $('#qualityBody tr').each(function () {
                const quantity = parseInt($(this).find('.quantity').val()) || 0;
                const accepted = parseInt($(this).find('.accepted').val()) || 0;
                const rejected = parseInt($(this).find('.rejected').val()) || 0;

                if  ((accepted + rejected) != quantity) {
                    alert('One or more rows have invalid Accepted + Rejected quantities.');
                    valid = false;
                    return false;
                }
            });

            if (!valid) {
                e.preventDefault(); // Stop form submission
            }
        });

    });
</script>
@endsection
