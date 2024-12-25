@extends('layouts.app')
@section('title', 'Manajemen Data Bagian')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-semibold mb-4">Data Bagian</h1>
            <button class="btn btn-success bg-green-500 text-white rounded-lg px-4 py-2 mb-4"
                id="addNewDataButton">Tambah Data</button>
        </div>
        <table class="min-w-full bg-white divide-y divide-gray-200 border" id="dataTables">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bagian</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dihapus pada</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-center"></tbody>
        </table>
    </div>
    <div id="dataModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4">Tambah Data</h2>
            <!-- Form Inputs -->
            <form id="dataForm" autocomplete="off">
                <input type="hidden" id="recordId" name="uuid">
                <input type="hidden" id="method" name="method">

                <div class="flex flex-col w-full mb-2">
                    <label for="code" class="block text-gray-700">Kode Bagian</label>
                    <input type="text" id="code" name="code" class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="name" class="block text-gray-700">Nama Bagian</label>
                    <input type="text" id="name" name="name" class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-4">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select id="status" name="status" class="border shadow-sm rounded-md py-2 px-3">
                        <option value="">Pilih Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Modal Buttons -->
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2 mr-2"
                        onclick="closeModal()">Batal</button>
                    <button type="button" id="save" class="bg-blue-500 text-white rounded-lg px-4 py-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function() {
            $('#dataTables').DataTable({
                ajax: '{!! route('superuser.department.datatable') !!}',
                dom: '<"flex flex-col md:flex-row gap-2 md:items-center justify-between mb-4"<"flex items-center"l><"flex items-center"f>><"max-w-full h-fit overflow-x-auto md:overflow-x-visible"rt><"flex items-center justify-between mt-4"<"text-gray-600"i><"flex items-center"p>>',
                lengthMenu: [10, 25, 50],
                pagingType: 'simple',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        width: 40
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'statusCast',
                        name: 'statusCast'
                    },
                    {
                        data: 'deletedAt',
                        name: 'deletedAt'
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
                    $('#dataTables_length label:contains("Show")').contents().filter(function() {
                        return this.nodeType === 3 && this.nodeValue.trim() === "Show";
                    }).remove();

                    $('#dataTables_filter label:contains("Search:")').contents().filter(function() {
                        return this.nodeType === 3 && this.nodeValue.trim() === "Search:";
                    }).remove();

                    $('#dataTables_length select')
                        .addClass('border border-gray-300 rounded-lg p-2')
                        .css('width', '80px');

                    $('#dataTables_filter input')
                        .addClass('border border-gray-300 rounded-lg p-2')
                        .attr('placeholder', 'Search...')
                        .css('display', 'inline-block');

                    $('.paginate_button')
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
            $('#addNewDataButton').click(function() {
                $('#dataModal').removeClass('hidden');
                $("#method").val('POST');
                $('#dataForm')[0].reset();
            });

            function closeModal() {
                $('#dataModal').addClass('hidden');
            }

            $('button[onclick="closeModal()"]').click(function() {
                closeModal();
            });

            // $('#dataModal').click(function(event) {
            //     if (event.target.id === 'dataModal') {
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
                $(this).prop('disabled', true).html('<span class="mr-2">Simpan</span><i class="fa fa-spinner fa-pulse fa-fw"></i>');

                const method = $("#method").val() === 'PUT' ? 'PUT' :
                    'POST';
                const url = method === "POST" ? "{{ route('superuser.department.store') }}" :
                    "{{ route('superuser.department.update') }}";

                $.ajax({
                    url: url,
                    type: method,
                    data: $("#dataForm").serialize(),
                    success: function(res) {
                        success(res.message);
                        closeModal();
                        $('#dataTables').DataTable().ajax.reload();
                        $('#dataForm')[0].reset();
                    },
                    error: function(err) {
                        handleError(err);
                    },
                    complete: function() {
                        $('#save').prop('disabled', false).text('Simpan');
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
                } else if (action === 'destroy') {
                    confirmDelete(data.uuid);
                } else if (action === 'restore') {
                    confirmRestore(data.uuid);
                } 
            });

            function populateForm(data) {
                $("#recordId").val(data.uuid);
                $("#code").val(data.code);
                $("#name").val(data.name);
                $("#status").val(data.status);
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Kamu yakin ingin menghapus permanent data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Tidak, jangan!'
                }).then((result) => {
                    if (result.value) {
                        deleteRecord(id);
                    }
                });
            }

            function deleteRecord(id) {
                const data = {
                    uuid: id
                };
                $.ajax({
                    url: "{{ route('superuser.department.destroy') }}",
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

            function confirmRestore(uuid) {
                Swal.fire({
                    title: 'Kamu yakin ingin mengembalikan data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, kembalikan!',
                    cancelButtonText: 'Tidak, jangan!'
                }).then((result) => {
                    if (result.value) {
                        restoreRecord(uuid);
                    }
                });
            }

            function restoreRecord(uuid) {
                const data = {
                    uuid: uuid
                };
                $.ajax({
                    url: "{{ route('superuser.department.restore') }}",
                    type: 'POST',
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
