@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="page-title">Form</h2>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">User</strong>
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
                        <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}"
                            method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @csrf
                                        @if (isset($user))
                                            @method('PUT')
                                        @endif
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">NIK</label>
                                            <input type="text" id="simpleinput" name="nik" class="form-control"
                                                value="{{ old('nik', $user->detail->nik ?? '') }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Address</label>
                                            <input type="text" id="simpleinput" name="address" class="form-control"
                                                value="{{ old('address', $user->detail->address ?? '') }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="example-palaceholder">Birth Date</label>
                                            <input type="date" id="example-palaceholder" name="birth_date"
                                                class="form-control" placeholder="Birth Date"
                                                value="{{ old('birth_date', isset($user->detail) ? $user->detail->birth_date : '') }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="example-password">Password</label>
                                            <input type="password" id="example-password" name="password"
                                                class="form-control"
                                                placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin ganti' : 'Password' }}">
                                        </div>
                                        <button class="btn btn-primary"
                                            type="submit">{{ isset($user) ? 'Update User' : 'Add New User' }}</button>
                                    </div> <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Name</label>
                                            <input type="text" id="simpleinput" name="name"
                                                value="{{ old('name', $user->detail->name ?? '') }}" class="form-control">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Telephone</label>
                                            <input type="text" id="simpleinput" name="no_hp"
                                                value="{{ old('no_hp', $user->detail->no_hp ?? '') }}" class="form-control">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="example-email">Email</label>
                                            <input type="email" id="example-email" name="email"
                                                value="{{ old('email', $user->email ?? '') }}" class="form-control"
                                                placeholder="Email">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="custom-select">Role</label>
                                            <select name="role" class="form-control" required>
                                                @php
                                                    $roles = ['Admin', 'Employee', 'User'];
                                                    $current = old('role', $user->role ?? '');
                                                @endphp

                                                @foreach ($roles as $r)
                                                    <option value="{{ $r }}"
                                                        {{ old('role', $user->role ?? '') == $r ? 'selected' : '' }}>
                                                        {{ ucfirst($r) }}
                                                    </option>
                                                @endforeach
                                            </select>
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
