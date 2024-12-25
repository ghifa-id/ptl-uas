@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', 'Riwayat Pengajuan')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-semibold mb-4">Riwayat Pengajuan</h1>
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
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Keputusan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-center"></tbody>
        </table>
    </div>

    <script>
        $(function() {
            $('#dataTables').DataTable({
                ajax: '{!! route('manager.booking.datatable') !!}',
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
                        width: 500
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

@endsection
