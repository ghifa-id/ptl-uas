@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <div class="container mx-auto p-4">
        <div class="flex justify-between line-clamp-3">
            <h1 class="text-2xl font-semibold mb-4">Pengajuan Menunggu Keputusan</h1>
        </div>
        <div id="emptyDisplay" class="flex flex-col justify-center items-center text-center">
            <dotlottie-player src="https://lottie.host/ca44b47f-1082-47b4-aa40-8f5b9ded1c49/stYScXPAhn.lottie"
                background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
            <span>Tidak ada pengajuan untuk saat ini, silahkan kembali lagi nanti!</span>
        </div>
        <table class="min-w-full bg-white divide-y divide-gray-200 border" id="dataTables">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                    </th>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                    <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-">Tujuan
                        Penggunaan</th>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Penggunaan</th>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Pengembalian</th>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-center"></tbody>
        </table>
    </div>

    <script>
        $(function() {
            $('#dataTables').DataTable({
                ajax: '{!! route('manager.dashboard.datatable') !!}',
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
                        name: 'user',
                        width: 150
                    },
                    {
                        data: 'merk',
                        name: 'merk',
                        width: 150
                    },
                    {
                        data: 'applicationDetail',
                        name: 'applicationDetail',
                        width: 600
                    },
                    {
                        data: 'startBooking',
                        name: 'startBooking',
                    },
                    {
                        data: 'endBooking',
                        name: 'endBooking',
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
                    $('#dataTables_wrapper')
                        .addClass('hidden');

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
            const fetchUrl = '{{ route('manager.dashboard.fetchdecision') }}';
            function fetchData() {
                $.ajax({
                    url: fetchUrl,
                    type: 'GET',
                    success: function(data) {
                        if (data.data) {
                            $('#dataTables_wrapper').removeClass('hidden');
                            $('#emptyDisplay').addClass('hidden');
                        } else {
                            $('#dataTables_wrapper').addClass('hidden');
                            $('#emptyDisplay').removeClass('hidden');
                        }
                    },
                    error: function(err) {
                        console.error('Error fetching data:', err);
                    }
                });
            }
            fetchData();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('table#dataTables tbody').on('click', 'td button', function(e) {
                const action = $(this).attr("data-mode");
                const data = $('#dataTables').DataTable().row($(this).parents('tr')).data();

                if (action === 'approved') {
                    approveBooking(data.uuid);
                } else if (action === 'refused') {
                    refuseBooking(data.uuid);
                }
            });

            function approveBooking(id) {
                Swal.fire({
                    title: 'Setuju atas pengajuan ini?',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setuju!',
                    cancelButtonText: 'Tidak, jangan!'
                }).then((result) => {
                    if (result.value) {
                        approveRecord(id);
                    }
                });
            }

            function approveRecord(id) {
                const data = {
                    uuid: id
                };
                $.ajax({
                    url: "{{ route('manager.booking.approved') }}",
                    type: 'POST',
                    data: data,
                    success: function(res) {
                        success(res.message);
                        $('#dataTables').DataTable().ajax.reload();
                        fetchData();
                    },
                    error: function(err) {
                        handleError(err);
                    }
                });
            }

            function refuseBooking(id) {
                Swal.fire({
                    title: 'Tolak pengajuan ini?',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, tolak!',
                    cancelButtonText: 'Tidak, jangan!'
                }).then((result) => {
                    if (result.value) {
                        refuseRecord(id);
                    }
                });
            }

            function refuseRecord(id) {
                const data = {
                    uuid: id
                };
                $.ajax({
                    url: "{{ route('manager.booking.refused') }}",
                    type: 'POST',
                    data: data,
                    success: function(res) {
                        success(res.message);
                        $('#dataTables').DataTable().ajax.reload();
                        fetchData();
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
