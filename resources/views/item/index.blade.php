@extends('menu/navbar')

@section('content')
<style>
    @media print {
    /* Sembunyikan semua kecuali tabel */
    body * {
        visibility: hidden;
    }

    .card, .card * {
        visibility: visible;
    }

    .card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    /* Sembunyikan tombol action & toolbar saat print */
    .toolbar,
    .dropdown,
    nav[aria-label="Table Paging"],
    .btn {
        display: none !important;
    }

    /* Supaya tabel kelihatan bordernya saat print */
    table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    table th, table td {
        border: 1px solid #000 !important;
        padding: 6px 10px !important;
        font-size: 12px !important;
    }

    /* Sembunyikan kolom gambar saat print (opsional) */
    th:first-child, td:first-child {
        display: none;
    }
}
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <!-- Small table -->
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">Table Item</h2>
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="toolbar">
                                        <form class="form">
                                            <div class="form-row">
                                                <div class="form-group col-auto mr-auto">
                                                    <label class="my-1 mr-2 sr-only"
                                                        for="inlineFormCustomSelectPref1">Show</label>
                                                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelectPref1">
                                                        <option value="">...</option>
                                                        <option value="1">12</option>
                                                        <option value="2" selected>32</option>
                                                        <option value="3">64</option>
                                                        <option value="3">128</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-auto">
                                                    <button type="button" class="btn mb-2 btn-secondary"
                                                        onclick="printTable()">
                                                        Print <span class="fe fe-download fe-16"></span>
                                                    </button>
                                                    <a href="{{ route('item.create') }}" class="btn mb-2 btn-primary">New
                                                        Item</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- table -->
                                    <table class="table table-borderless table-hover">
                                        <thead>
                                            <tr>
                                                <th>Picture</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data as $item)
                                                <tr>
                                                    <td><img src="{{ asset('storage/' . $item->photo_path) }}"
                                                            alt="Item Image" style="max-width: 100px; max-height: 100px;">
                                                    </td>
                                                    <td>{{ $item->code_slug ?? '-' }}</td>
                                                    <td>{{ $item->name ?? '-' }}</td>
                                                    <td>{{ $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>{{ $item->category->name ?? '-' }}</td>
                                                    <td>{{ $item->item_type ?? '-' }}</td>
                                                    <td>{{ $item->location->name . ' - ' . $item->location->detail ?? '-' }}
                                                    </td>
                                                    <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="text-muted sr-only">Action</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item"
                                                                href="{{ route('item.detail', $item->id) }}">Detail</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('item.edit', $item->id) }}">Edit</a>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="event.preventDefault(); if(confirm('Are you sure?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }">
                                                                Delete
                                                            </a>
                                                            <form id="delete-form-{{ $item->id }}"
                                                                action="{{ route('item.destroy', $item->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        There is no tool data
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <nav aria-label="Table Paging" class="mb-0 text-muted">
                                        <ul class="pagination justify-content-center mb-0">
                                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div> <!-- customized table -->
                    </div> <!-- end section -->
                </div> <!-- .col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->
    </main> <!-- main -->
<script>
    function printTable() {
        window.print();
    }
</script>
@endsection
