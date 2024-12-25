@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', 'Data Booking Kendaraan')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-semibold mb-4">Booking Kendaraan</h1>
            <button class="btn btn-success bg-green-500 text-white rounded-lg px-4 py-2 mb-4" id="addNewDataButton">Ajukan
                Penggunaan Kendaraan</button>
        </div>
        <table class="min-w-full bg-white divide-y divide-gray-200 border" id="dataTables">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Merk
                        Kendaraan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Plat Nomor
                        Kendaraan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Penggunaan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Pengembalian</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Keputusan</th>
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
                <div class="flex flex-col w-full mb-2">
                    <label for="vehicle_id" class="block text-gray-700">Kendaraan</label>
                    <select id="vehicle_id" name="vehicle_id"
                        class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kendaraan</option>
                        @foreach ($vehicle as $data)
                            <option value="{{ $data->uuid }}">{{ $data->merk . ' - ' . $data->plat_number }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="application_detail" class="block text-gray-700">Tujuan Penggunaan Kendaraan</label>
                    <textarea id="application_detail" name="application_detail"
                        class="border shadow-sm rounded-md py-2 px-3 h-24 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="start_booking" class="block text-gray-700">Waktu Penggunaan</label>
                    <input type="datetime-local" id="start_booking" name="start_booking"
                        class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        min="{{ Carbon::now()->timezone('Asia/Jakarta') }}">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="end_booking" class="block text-gray-700">Waktu Pengembalian</label>
                    <input type="datetime-local" id="end_booking" name="end_booking"
                        class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        min="{{ Carbon::now()->timezone('Asia/Jakarta') }}">
                </div>
                <!-- Modal Buttons -->
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2 mr-2"
                        onclick="closeModal()">Batal</button>
                    <button type="button" id="save" class="bg-blue-500 text-white rounded-lg px-4 py-2">Ajukan
                        Sekarang!</button>
                </div>
            </form>
        </div>
    </div>
    <div id="dataModalInfo" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <!-- Modal Content -->
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4">Pengajuan <span id="detailStatus"></span></h2>
            <div class="absolute z-0 right-4 top-4">
                <i id="iconStatus" class="fa fa-3x" aria-hidden="true"></i>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex flex-col w-full mb-2">
                        <label for="startBooking" class="block text-gray-700">Waktu Penggunaan</label>
                        <input type="text" disabled id="startBooking" name="startBooking"
                            class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex flex-col w-full mb-2">
                        <label for="endBooking" class="block text-gray-700">Waktu Pengembalian</label>
                        <input type="text" disabled id="endBooking" name="endBooking"
                            class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex flex-col w-full mb-2">
                    <label for="areaDetail" class="block text-gray-700">Tujuan Penggunaan Kendaraan</label>
                    <textarea id="areaDetail" disabled name="areaDetail"
                        class="border shadow-sm rounded-md py-2 px-3 h-36 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <button type="button" class="bg-blue-500 text-white rounded-lg px-4 py-2 mt-4 mr-2"
                    onclick="closeModalInfo()">OK</button>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('#dataTables').DataTable({
                ajax: '{!! route('applicant.booking.datatable') !!}',
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
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'merk',
                        name: 'merk'
                    },
                    {
                        data: 'plat_number',
                        name: 'plat_number'
                    },
                    {
                        data: 'startBooking',
                        name: 'startBooking'
                    },
                    {
                        data: 'endBooking',
                        name: 'endBooking'
                    },
                    {
                        data: 'statusCast',
                        name: 'statusCast'
                    },
                    {
                        data: 'decidedAt',
                        name: 'decidedAt'
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
                $('#dataForm')[0].reset();
            });

            function closeModal() {
                $('#dataModal').addClass('hidden');
            }

            $('button[onclick="closeModal()"]').click(function() {
                closeModal();
            });

            function closeModalInfo() {
                $("#iconStatus").removeClass('fa-check-circle text-green-500 fa-exclamation-circle text-red-500 fa-clock-o text-yellow-500 fa-car text-blue-500 fa-ban text-red-500');
                $('#dataModalInfo').addClass('hidden');
            }

            $('button[onclick="closeModalInfo()"]').click(function() {
                closeModalInfo();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#save').click(function(e) {
                e.preventDefault();
                $(this).prop('disabled', true).html(
                    '<span class="mr-2">Ajukan Sekarang!</span><i class="fa fa-spinner fa-pulse fa-fw"></i>'
                );
                $.ajax({
                    url: '{{ route('applicant.booking.store') }}',
                    type: 'POST',
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
                        $('#save').prop('disabled', false).text('Ajukan Sekarang!');
                    }
                });
            });

            $('table#dataTables tbody').on('click', 'td button', function(e) {
                const action = $(this).attr("data-mode");
                const data = $('#dataTables').DataTable().row($(this).parents('tr')).data();

                if (action === 'cancel') {
                    confirmCancel(data.uuid);
                } else if (action === 'detail') {
                    popModalInfo(data)
                    $('#dataModalInfo').removeClass('hidden');
                }
            });

            function popModalInfo(data) {
                $("#areaDetail").val(data.application_detail);
                $("#startBooking").val(data.startBook);
                $("#endBooking").val(data.endBook);
                $("#detailStatus").text(data.status === 'approved' ? 'Disetujui' : data.status === 'refused' ? 'Ditolak' : data.status === 'waiting' ?  'Menunggu' : data.status === 'returned' ?  'Selesai' : 'Di Batalkan');
                $("#iconStatus").addClass(data.status === 'approved' ? 'fa-check-circle text-green-500' : data.status === 'refused' ? 'fa-exclamation-circle text-red-500' : data.status === 'waiting' ?  'fa-clock-o text-yellow-500' : data.status === 'returned' ?  'fa-car text-blue-500' : 'fa-ban text-red-500');
            }
            // function popModalInfo(id) {
            //     const data = {
            //         uuid: id
            //     };
            //     $.ajax({
            //         url: "{{ route('applicant.booking.cancel') }}",
            //         type: 'POST',
            //         data: data,
            //         success: function(res) {
            //             success(res.message);
            //             $('#dataTables').DataTable().ajax.reload();
            //         },
            //         error: function(err) {
            //             handleError(err);
            //         }
            //     });
            // }

            function confirmCancel(id) {
                Swal.fire({
                    title: 'Kamu yakin ingin membatalkan pengajuan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Tidak, jangan!'
                }).then((result) => {
                    if (result.value) {
                        cancelRecord(id);
                    }
                });
            }

            function cancelRecord(id) {
                const data = {
                    uuid: id
                };
                $.ajax({
                    url: "{{ route('applicant.booking.cancel') }}",
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
