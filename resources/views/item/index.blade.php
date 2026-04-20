@extends('menu/navbar')

@section('content')
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12 my-4">
                            <h2 class="h4 mb-1">Table Item</h2>
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="toolbar">
                                        <form class="form">
                                            <div class="form-row">
                                                <div class="form-group col-auto mr-auto"></div>
                                                @if ($role == 'admin')
                                                    <div class="form-group col-auto">
                                                        <a href="{{ route('item.export') }}"
                                                            class="btn mb-2 btn-primary">
                                                            Export Excel
                                                            <span class="fe fe-download fe-16"></span>
                                                        </a>
                                                        <a href="{{ route('item.create') }}"
                                                            class="btn mb-2 btn-primary">New Item</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>

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
                                                    <td>
                                                        <img src="{{ asset('storage/' . $item->photo_path) }}"
                                                            alt="Item Image"
                                                            style="max-width: 100px; max-height: 100px;">
                                                    </td>
                                                    <td>{{ $item->code_slug ?? '-' }}</td>
                                                    <td>{{ $item->name ?? '-' }}</td>
                                                    <td>{{ $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-' }}</td>
                                                    <td>{{ $item->category->name ?? '-' }}</td>
                                                    <td>{{ $item->item_type ?? '-' }}</td>
                                                    <td>{{ isset($item->location) ? $item->location->name . ' - ' . $item->location->detail : '-' }}</td>
                                                    <td>
                                                        <button class="btn btn-sm dropdown-toggle more-horizontal"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="text-muted sr-only">Action</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item"
                                                                href="{{ route('item.detail', $item->id) }}">Detail</a>
                                                            @if ($role == 'admin')
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
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        There is no tool data
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection