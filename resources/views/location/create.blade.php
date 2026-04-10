@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="page-title">Form Create Location</h2>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Create New Location</strong>
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
                        <form action="{{ isset($location) ? route('location.update', $location->location_code) : route('location.store') }}"
                            method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @csrf
                                        @if (isset($location))
                                            @method('PUT')
                                        @endif
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Code</label>
                                            <input type="text" id="simpleinput" name="location_code" class="form-control"
                                                value="{{ old('location_code', $location->location_code ?? '') }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Detail</label>
                                            <input type="text" id="simpleinput" name="detail"
                                                value="{{ old('detail', $location->detail ?? '') }}" class="form-control">
                                        </div>
                                        <button class="btn btn-primary"
                                            type="submit">{{ isset($location) ? 'Update Location' : 'Add New Location' }}</button>
                                    </div> <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Name</label>
                                            <input type="text" id="simpleinput" name="name" class="form-control"
                                                value="{{ old('name', $location->name ?? '') }}">
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
