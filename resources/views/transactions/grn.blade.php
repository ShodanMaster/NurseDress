@extends('app.layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="card-title">GRN Entry</h3>
            </div>
            <form method="POST" action="{{ route('transaction.grnstore') }}" id="grn-form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grn-number" class="form-label">GRN Number</label>
                                <input class="form-control" type="text" readonly name="grn_number" id="grn-number" value="{{$grnNumber}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location" class="form-label">Location</label>
                                <select class="form-control" name="location_id" id="location" required>
                                    <option value="" selected disabled> --Select Location--</option>

                                    @forelse ($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                    @empty
                                        <option value="" disabled>No Locations</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice-no" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" name="invoiceno" id="invoice-no">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice-date" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" name="invoicedate" id="invoice-date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="5"></textarea>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="item" class="form-label">Item</label>
                            <select class="form-control" name="item" id="item">
                                <option value="" selected disabled>--Select Item--</option>

                                @forelse ($items as $item)
                                    <option value="{{ $item->id }}" data-amount="{{ $item->amount }}">{{ $item->title }}</option>
                                @empty
                                    <option value="" disabled>No Items</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" readonly class="form-control" id="amount" name="amount">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" id="quantity">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total-amount" class="form-label">Total Amount</label>
                                <input type="number" readonly class="form-control" name="total_amount" id="total-amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="total-barcode" class="form-label">Total Barcode</label>
                                <input type="number" readonly class="form-control" name="total_barcode" id="total-barcode" required>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" id="add-to-grid" class="btn btn-primary">Add to Grid</button>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="table-responsive">
                        <table class="table" id="grngrid">
                            <thead>
                                <tr>
                                    <th>Sl.no</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>No of Barcodes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="grngridbody">
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Submit GRN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemSelect = document.getElementById('item');
        const amountInput = document.getElementById('amount');
        const quantityInput = document.getElementById('quantity');
        const totalAmountInput = document.getElementById('total-amount');
        const totalBarcodeInput = document.getElementById('total-barcode');
        const addToGridButton = document.getElementById('add-to-grid');
        const gridBody = document.getElementById('grngridbody');
        const form = document.getElementById('grn-form');

        function updateAmountAndTotal() {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const amount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
            const quantity = parseInt(quantityInput.value) || 0;

            amountInput.value = amount;
            totalAmountInput.value = (amount * quantity).toFixed(2);
        }

        itemSelect.addEventListener('change', updateAmountAndTotal);
        quantityInput.addEventListener('input', updateAmountAndTotal);

        let rowCount = 0;
        let totalBarcodes = 0;

        addToGridButton.addEventListener('click', function () {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const itemId = itemSelect.value;
            const itemName = selectedOption.text;
            const quantity = parseInt(quantityInput.value) || 0;
            const amount = parseInt(amountInput.value) || 0;

            if (!itemId || quantity <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Input',
                    text: 'Please select an item and enter a valid quantity.'
                });
                return;
            }

            const existingRows = gridBody.querySelectorAll('tr');
            for (let row of existingRows) {
                const existingItemId = row.querySelector('input[name*="[item_id]"]').value;
                if (existingItemId === itemId) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Item',
                        text: 'This item is already added to the grid.'
                    });
                    return;
                }
            }

            rowCount++;
            totalBarcodes += quantity;
            totalBarcodeInput.value = totalBarcodes;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td>${itemName}<input type="hidden" name="items[${rowCount}][item_id]" value="${itemId}"></td>
                <td>${quantity}<input type="hidden" name="items[${rowCount}][quantity]" value="${quantity}"></td>
                <td>${quantity}<input type="hidden" name="items[${rowCount}][barcodes]" value="${quantity}"></td>
                <input type="hidden" name="items[${rowCount}][amount]" value="${amount}">
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            `;

            gridBody.appendChild(newRow);

            itemSelect.selectedIndex = 0;
            amountInput.value = '';
            quantityInput.value = '';
            totalAmountInput.value = '';
        });

        gridBody.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                const removedBarcodes = parseInt(row.children[3].textContent) || 0;
                totalBarcodes -= removedBarcodes;
                totalBarcodeInput.value = totalBarcodes;

                row.remove();

                Array.from(gridBody.children).forEach((row, index) => {
                    row.children[0].textContent = index + 1;

                    row.querySelectorAll('input').forEach(input => {
                        if (input.name.includes('[item_id]')) {
                            input.name = `items[${index + 1}][item_id]`;
                        } else if (input.name.includes('[quantity]')) {
                            input.name = `items[${index + 1}][quantity]`;
                        } else if (input.name.includes('[barcodes]')) {
                            input.name = `items[${index + 1}][barcodes]`;
                        } else if (input.name.includes('[amount]')) {
                            input.name = `items[${index + 1}][amount]`;
                        }
                    });
                });

                rowCount = gridBody.children.length;
            }
        });

        form.addEventListener('submit', function (e) {
            const gridHasRows = gridBody.querySelectorAll('tr').length > 0;

            if (!gridHasRows) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Empty Grid',
                    text: 'Please add at least one item to the grid before submitting.'
                });
            }
        });
    });
</script>
@endsection
