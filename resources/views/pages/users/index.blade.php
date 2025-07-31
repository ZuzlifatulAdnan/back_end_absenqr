@extends('layouts.app')

@section('title', 'Users')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/d') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            @include('layouts.alert')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Data Users</h4>
                                </div>
                                <div class="card-body">
                                    <div class="p-2">
                                        <div class="float-left">
                                            <div class="section-header-button">
                                                <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah</a>
                                            </div>
                                        </div>
                                        <div class="float-right">
                                            <form action="{{ route('user.index') }}" method="GET">
                                                <div class="input-group">
                                                    <select name="role" class="form-control"
                                                        onchange="this.form.submit()">
                                                        <option value=""
                                                            {{ request('role') == '' ? 'selected' : '' }}>Semua Role
                                                        </option>
                                                        <option value="Admin"
                                                            {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin
                                                        </option>
                                                        <option value="Guru"
                                                            {{ request('role') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                                        <option value="Siswa"
                                                            {{ request('role') == 'Siswa' ? 'selected' : '' }}>Orang Tua
                                                        </option>
                                                    </select>
                                                    <input type="text" class="form-control" placeholder="Search"
                                                        name="name" value="{{ request('name') }}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary"><i
                                                                class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="clearfix  divider mb-3"></div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-lg" id="table-1">
                                            <tr>
                                                <th style="width: 3%">No</th>
                                                <th class="text-center">Image</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th style="width: 5%" class="text-center">Action</th>
                                            </tr>
                                            @foreach ($users as $index => $user)
                                                <tr>
                                                    <td>
                                                        {{ $users->firstItem() + $index }}
                                                    </td>
                                                    <td class="text-center">
                                                        <img alt="image"
                                                            src="{{ $user->image ? asset('img/user/' . $user->image) : asset('img/avatar/avatar-1.png') }}"
                                                            class="rounded-circle" width="35" height="35"
                                                            data-toggle="tooltip" title="avatar">
                                                    </td>
                                                    <td>
                                                        {{ $user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $user->email }}
                                                    </td>
                                                    <td>
                                                        {{ $user->role }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('user.edit', $user) }}"
                                                                class="btn btn-sm btn-icon btn-primary m-1"><i
                                                                    class="fas fa-edit"></i></a>
                                                            <form action="{{ route('user.destroy', $user) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn btn-sm btn-icon m-1 btn-danger confirm-delete"
                                                                    data-toggle="tooltip" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        <div class="card-footer d-flex justify-content-between">
                                            <span>
                                                Showing {{ $users->firstItem() }}
                                                to {{ $users->lastItem() }}
                                                of {{ $users->total() }} entries
                                            </span>
                                            <div class="paginate-sm">
                                                {{ $users->onEachSide(0)->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
    @endif

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset() }}"></script> --}}
    {{-- <script src="{{ asset() }}"></script> --}}
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('js/page/components-table.js') }}"></script>
@endpush
