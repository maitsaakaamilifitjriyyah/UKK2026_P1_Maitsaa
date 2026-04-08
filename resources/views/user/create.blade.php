@extends('menu/navbar')

@section('content')
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="page-title">Form Create User</h2>
            <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">Create New User</strong>
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                    <label for="simpleinput">NIK</label>
                    <input type="text" id="simpleinput" name="nik" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                    <label for="simpleinput">Address</label>
                    <input type="text" id="simpleinput" name="address" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                    <label for="example-palaceholder">Birth Date</label>
                    <input type="date" id="example-palaceholder" name="birth_date" class="form-control" placeholder="Birth Date">
                    </div>
                    <div class="form-group mb-3">
                    <label for="example-password">Password</label>
                    <input type="password" id="example-password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button class="btn btn-primary" type="submit">Submit form</button>
                </div> <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                    <label for="simpleinput">Name</label>
                    <input type="text" id="simpleinput" name="name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                    <label for="simpleinput">Telephone</label>
                    <input type="text" id="simpleinput" name="telephone" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                    <label for="example-email">Email</label>
                    <input type="email" id="example-email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group mb-3">
                        <label for="custom-select">Role</label>
                        <select class="custom-select" id="custom-select">
                          <option selected="">Select role</option>
                          <option value="admin">Admin</option>
                          <option value="petugas">Petugas</option>
                          <option value="peminjam">Peminjam</option>
                        </select>
                      </div>
                </div>
                </div>
            </div>
            </div> <!-- / .card -->
</div> <!-- .container-fluid -->
</main> <!-- main -->
@endsection