@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-semibold mb-4">Data Pengguna</h1>
            <button class="btn btn-success bg-green-500 text-white rounded-lg px-4 py-2 mb-4"
                id="addNewMatkulButton">Registrasi Pengguna Baru</button>
        </div>
        <table class="min-w-full bg-white divide-y divide-gray-200 border" id="dataTables">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        Pengguna</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor
                        Telepon</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bagian</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-center"></tbody>
        </table>
    </div>
    <div id="dataModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4">Registrasi Pengguna</h2>
            <!-- Form Inputs -->
            <form id="dataForm" autocomplete="off">
                <input type="hidden" id="recordId" name="uuid">
                <input type="hidden" id="method" name="method">

                <div class="flex flex-col w-full mb-2">
                    <label for="name" class="block text-gray-700">Nama</label>
                    <input type="text" id="name" name="name" class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="username" class="block text-gray-700">Nama Pengguna</label>
                    <input type="text" id="username" name="username" class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="phone_number" class="block text-gray-700">Nomor Telepon</label>
                    <input type="text" id="phone_number" name="phone_number"
                        class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="department_id" class="block text-gray-700">Bagian</label>
                    <select id="department_id" name="department_id" class="border shadow-sm rounded-md py-2 px-3">
                        <option value="">Pilih Bagian</option>
                        @foreach ($department as $data)
                            <option value="{{ $data->uuid }}">{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col w-full mb-4">
                    <label for="role" class="block text-gray-700">Jabatan</label>
                    <select id="role" name="role" class="border shadow-sm rounded-md py-2 px-3">
                        <option value="">Pilih Jabatan</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="kasubag">Kepala Bagian</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div id="inputStatus" class="flex flex-col w-full mb-2 hidden">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select id="status" name="status" class="border shadow-sm rounded-md py-2 px-3">
                        <option value="">Pilih Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Modal Buttons -->
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2 mr-2"
                        onclick="closeModal()">Batal</button>
                    <button type="button" id="save"
                        class="bg-blue-500 text-white rounded-lg px-4 py-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalInfoLogin" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4 text-center">Info Login Pengguna</h2>
            <div class="flex flex-col w-full mb-2">
                <label for="username" class="block text-gray-700">Nama Pengguna</label>
                <input type="text" id="textUsername" readonly class="border shadow-sm rounded-md py-2 px-3">
            </div>
            <div class="flex flex-col w-full mb-2">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="text" id="textEmail" readonly class="border shadow-sm rounded-md py-2 px-3">
            </div>
            <div class="flex flex-col w-full mb-2">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="text" id="textPassword" readonly class="border shadow-sm rounded-md py-2 px-3">
            </div>
            <div class="flex justify-center">
                <button type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2"
                    onclick="closeModalInfoLogin()">Konfirmasi</button>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('administrator.user.datatable') !!}',
                dom: '<"flex items-center justify-between mb-4"<"flex items-center"l><"flex items-center ml-2"f>><"mt-2"rt><"flex items-center justify-between mt-4"<"text-gray-600"i><"flex items-center"p>>',
                lengthMenu: [10, 25, 50],
                pagingType: 'simple',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: 40
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'roleCast',
                        name: 'roleCast'
                    },
                    {
                        data: 'statusCast',
                        name: 'statusCast'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: 73
                    }
                ],
                initComplete: function() {
                    $('#dataTables_length select')
                        .addClass('border border-gray-300 rounded-lg p-2')
                        .css('width', '80px');

                    $('#dataTables_filter input')
                        .addClass('border border-gray-300 rounded-lg p-2 ml-2')
                        .attr('placeholder', 'Search...')
                        .css('display', 'inline-block');

                    $('.dataTables_paginate .paginate_button')
                        .addClass(
                            'border border-gray-300 rounded-lg p-2 mx-1 hover:bg-gray-200 text-gray-600'
                        );

                    $('#dataTables_info').addClass('text-gray-600');
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#addNewMatkulButton').click(function() {
                $('#dataModal').removeClass('hidden');
                $("#method").val('POST');
                $('#dataForm')[0].reset();
            });

            function closeModal() {
                $('#dataModal').addClass('hidden');
                $("#inputStatus").addClass('hidden');
                $('#username').prop('disabled', false);
            }

            function closeModalInfoLogin() {
                $('#modalInfoLogin').addClass('hidden');
            }

            $('button[onclick="closeModal()"]').click(function() {
                closeModal();
            });

            $('button[onclick="closeModalInfoLogin()"]').click(function() {
                closeModalInfoLogin();
            });

            // $('#modalInfoLogin').click(function(event) {
            //     if (event.target.id === 'modalInfoLogin') {
            //         closeModal();
            //     }
            // });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#save').click(function(e) {
                e.preventDefault();

                const method = $("#method").val() === 'PUT' ? 'PUT' :
                    'POST';
                const url = method === "POST" ? "{{ route('administrator.user.store') }}" :
                    "{{ route('administrator.user.update') }}";

                $.ajax({
                    url: url,
                    type: method,
                    data: $("#dataForm").serialize(),
                    success: function(res) {
                        success(res.message);
                        closeModal();
                        $('#dataTables').DataTable().ajax.reload();
                        $('#dataForm')[0].reset();
                        if(res.data.act === 'store') {
                            $("#textUsername").val(res.data.record.username);
                            $("#textEmail").val(res.data.record.email);
                            $("#textPassword").val(res.data.record.first_password);
                            $('#modalInfoLogin').removeClass('hidden');
                        }
                    },
                    error: function(err) {
                        handleError(err);
                    }
                });
            });

            $('table#dataTables tbody').on('click', 'td button', function(e) {
                const action = $(this).attr("data-mode");
                const data = $('#dataTables').DataTable().row($(this).parents('tr')).data();

                if (action === 'edit') {
                    populateForm(data);
                    $("#method").val('PUT');
                    $('#dataModal').removeClass('hidden');
                    $('#username').prop('disabled', true);
                    if(data.status !== 'set_password') {
                        $("#inputStatus").removeClass('hidden');
                    }
                } else if (action === 'destroy') {
                    confirmDelete(data.uuid);
                } else {
                    popInfoLogin(data);
                    $('#modalInfoLogin').removeClass('hidden');
                }
            });

            function populateForm(data) {
                $("#recordId").val(data.uuid);
                $("#name").val(data.name);
                $("#username").val(data.username);
                $("#email").val(data.email);
                $("#phone_number").val(data.phone_number);
                $("#department_id").val(data.department_id);
                $("#role").val(data.role);
                $("#status").val(data.status);
            }

            function popInfoLogin(data) {
                $("#textUsername").val(data.username);
                $("#textEmail").val(data.email);
                $("#textPassword").val(data.first_password);
            }

            function confirmDelete(uuid) {
                Swal.fire({
                    title: 'Are you sure you want to delete this record?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        deleteRecord(uuid);
                    }
                });
            }

            function deleteRecord(uuid) {
                const data = {
                    uuid: uuid
                };
                $.ajax({
                    url: "{{ route('administrator.user.destroy') }}",
                    type: 'DELETE',
                    data: data,
                    success: function(res) {
                        success(res.message);
                        $('#dataTables').DataTable().ajax.reload();
                    },
                    error: function(err) {
                        handleError(err);
                    }
                });
            }

            function handleError(err) {
                if (Array.isArray(err.responseJSON.message)) {
                    err.responseJSON.message.forEach(error);
                } else {
                    error(err.responseJSON.message);
                }
            }
        });
    </script>
@endsection
