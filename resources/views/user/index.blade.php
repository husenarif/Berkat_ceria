@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data User</h4>
                <div>
                    <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah User</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            <th class="text-center text-nowrap">Nama User</th>
                            <th class="text-center text-nowrap">Email</th>
                            <th class="text-center text-nowrap">Role</th>
                            <th class="text-center text-nowrap">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $item)
                            <tr>
                                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email ?? '-' }}</td>
                                <td>
                                    {{ $item->role->role_name ?? '-' }}
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('user.edit', $item->id) }}">Edit</a>
                                            </li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('user.destroy', $item->id) }}"
                                                    data-confirm-delete="true">Hapus</a>
                                            </li>
                                                <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                    data-bs-target="#roleModal{{ $item->id }}">
                                                    Ganti Role
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Ganti Role --}}
    @foreach ($users as $item)
        <div class="modal fade" id="roleModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="roleLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roleLabel{{ $item->id }}">Ganti Role untuk {{ $item->name }}?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.update-role') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                            <div class="mb-3">
                                <label for="role_id_{{ $item->id }}" class="form-label">Tentukan Role Akses</label>
                                <select name="role_id" id="role_id_{{ $item->id }}" class="form-control">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ganti Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
       {{-- TAMBAHKAN BLOK SCRIPT INI --}}
    @push('scripts')
        @if($users->isNotEmpty()) {{-- Opsional: hanya inisialisasi jika ada data --}}
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        @endif
    @endpush
@endsection
