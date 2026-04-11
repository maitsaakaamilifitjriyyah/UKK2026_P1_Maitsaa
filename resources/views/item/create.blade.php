@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="page-title">Form Create Item</h2>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Create New Item</strong>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger shadow">
                                <strong class="mb-2">Waduh! Ada masalah:</strong>
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
                                            <label for="custom-select">Item Type</label>
                                            <select name="item_type" class="form-control" required>
                                                <option value="">-- Select Item Type --</option>
                                                <option value="single"
                                                    {{ old('item_type', $item->item_type ?? '') == 'single' ? 'selected' : '' }}>
                                                    Single</option>
                                                <option value="bundle"
                                                    {{ old('item_type', $item->item_type ?? '') == 'bundle' ? 'selected' : '' }}>
                                                    Bundle</option>
                                                <option value="bundle_tool"
                                                    {{ old('item_type', $item->item_type ?? '') == 'bundle_tool' ? 'selected' : '' }}>
                                                    Bundle Tool</option>
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
                                            <label for="customFile">Custom file input</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile" name="photo_path">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary"
                                            type="submit">{{ isset($item) ? 'Update Item' : 'Add New Item' }}</button>
                                    </div> <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Name</label>
                                            <input type="text" id="simpleinput" name="name"
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
                                            <label for="example-email">Description</label>
                                            <textarea id="example-email" name="description" class="form-control" placeholder="Description">{{ old('description', $item->description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div> <!-- /.row -->
                            </div> <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div> <!-- / .card -->
        </div> <!-- .container-fluid -->
    </main> <!-- main -->
@endsection
