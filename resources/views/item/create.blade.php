@extends('menu/navbar')

@section('content')
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">{{ isset($item) ? 'Edit Item' : 'Create New Item' }}</h2>
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">{{ isset($item) ? 'Edit Item' : 'Create New Item' }}</strong>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger shadow">
                        <strong class="mb-2">Ouch! There are some issues:</strong>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ isset($item) ? route('item.update', $item->id) : route('item.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @csrf
                                    @if (isset($item))
                                    @method('PUT')
                                    @endif
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Code</label>
                                        <input type="text" id="simpleinput" name="code_slug" class="form-control"
                                            value="{{ old('code_slug', $item->code_slug ?? '') }}" placeholder="Code">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="itemTypeSelect">Item Type</label>
                                        {{-- ↓ Tambah id="itemTypeSelect" --}}
                                        <select name="item_type" class="form-control" id="itemTypeSelect" required>
                                            <option value="">-- Select Item Type --</option>
                                            <option value="single"
                                                {{ old('item_type', $item->item_type ?? '') == 'single' ? 'selected' : '' }}>
                                                Single</option>
                                            <option value="bundle"
                                                {{ old('item_type', $item->item_type ?? '') == 'bundle' ? 'selected' : '' }}>
                                                Bundle</option>
                                        </select>
                                        @error('item_type')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="custom-select">Location</label>
                                        <select name="location_code" class="form-control" required>
                                            <option value="">-- Select Location --</option>
                                            @foreach ($location as $l)
                                            <option value="{{ $l->location_code }}"
                                                {{ old('location_code') == $l->location_code ? 'selected' : '' }}>
                                                {{ ucfirst($l->name) }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('location_code')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="customFile">Photo</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="photo_path">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Name</label>
                                        <input type="text" name="name"
                                            value="{{ old('name', $item->name ?? '') }}" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="custom-select">Category</label>
                                        <select name="category_id" class="form-control" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach ($category as $c)
                                            <option value="{{ $c->id }}"
                                                {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                                {{ ucfirst($c->name) }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                value="{{ old('price', $item->price ?? '') }}" 
                                                placeholder="Enter purchase price" min="0">
                                            @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="example-email">Description</label>
                                        <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- Bundle Section --}}
                            <div id="bundleSection" style="display: none; border-top: 1px solid #dee2e6; margin-top: 1rem; padding-top: 1rem;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Bundle Tools</h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalBundle">
                                        + Add Bundle
                                    </button>
                                </div>
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th style="width: 120px;">Qty</th>
                                            <th style="width: 180px;">Price (Rp)</th>
                                            <th>Description</th>
                                            <th style="width: 60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="bundleItemsContainer">
                                        <tr id="bundleEmptyRow">
                                            <td colspan="5" class="text-center text-muted">No bundle items added yet</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Modal Bundle --}}
                            <div class="modal fade" id="modalBundle" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Bundle Tool</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group mb-3">
                                                <label>Name</label>
                                                <input type="text" id="modalName" class="form-control" placeholder="Bundle Name">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Qty</label>
                                                <input type="number" id="modalQty" class="form-control" value="1" min="1">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Price (Rp)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" id="modalPrice" class="form-control" value="0" min="0">
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Description</label>
                                                <textarea id="modalDescription" class="form-control" placeholder="Description" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" id="btnSaveBundle">Add to Bundle</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">
                                {{ isset($item) ? 'Update Item' : 'Add New Item' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    const itemTypeSelect = document.getElementById('itemTypeSelect');
    const bundleSection = document.getElementById('bundleSection');
    const container = document.getElementById('bundleItemsContainer');

    itemTypeSelect.addEventListener('change', function() {
        bundleSection.style.display = this.value === 'bundle' ? 'block' : 'none';
    });
    if (itemTypeSelect.value === 'bundle') bundleSection.style.display = 'block';

    document.getElementById('btnSaveBundle').addEventListener('click', function() {
        const name = document.getElementById('modalName').value.trim();
        const qty = document.getElementById('modalQty').value;
        const price = document.getElementById('modalPrice').value;
        const desc = document.getElementById('modalDescription').value;

        if (!name) {
            alert('Name is required.');
            return;
        }

        // Hapus empty row jika ada
        const emptyRow = document.getElementById('bundleEmptyRow');
        if (emptyRow) emptyRow.remove();

        const row = document.createElement('tr');
        row.classList.add('bundle-item');
        row.innerHTML = `
            <td>
                ${name}
                <input type="hidden" name="bundle_names[]" value="${name}">
            </td>
            <td>
                <input type="number" name="bundle_qty[]" class="form-control" value="${qty}" readonly>
            </td>
            <td>
                <input type="number" name="bundle_price[]" class="form-control bundle-price-cell" value="${price}" readonly>
            </td>
            <td>
                <input type="text" name="bundle_desc[]" class="form-control" value="${desc}" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-remove-bundle">✕</button>
            </td>
        `;
        container.appendChild(row);

        // Reset modal
        document.getElementById('modalName').value = '';
        document.getElementById('modalQty').value = 1;
        document.getElementById('modalPrice').value = 0;
        document.getElementById('modalDescription').value = '';

        $('#modalBundle').modal('hide');
    });
</script>

@endsection