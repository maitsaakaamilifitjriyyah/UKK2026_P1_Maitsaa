@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="page-title">Form Create Category</h2>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Create New Category</strong>
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
                        <form action="{{ isset($category) ? route('category.update', $category->id) : route('category.store') }}"
                            method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @csrf
                                        @if (isset($category))
                                            @method('PUT')
                                        @endif
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Name</label>
                                            <input type="text" id="simpleinput" name="name" class="form-control"
                                                value="{{ old('name', $category->name ?? '') }}">
                                        </div>
                                        <button class="btn btn-primary"
                                            type="submit">{{ isset($category) ? 'Update Category' : 'Add New Category' }}</button>
                                    </div> <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Description</label>
                                            <input type="text" id="simpleinput" name="description"
                                                value="{{ old('description', $category->description ?? '') }}" class="form-control">
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
