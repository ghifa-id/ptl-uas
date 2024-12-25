@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', 'Laporan Pengembalian Kendaraan')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-semibold mb-4">Laporan Pengembalian Kendaraan</h1>
            <button class="btn btn-success bg-green-500 disabled:bg-gray-500 text-white rounded-lg px-4 py-2 mb-4"
                id="addNewDataButton">Buat Laporan Pengembalian</button>
        </div>
        <table class="min-w-full bg-white divide-y divide-gray-200 border" id="dataTables">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Merk & Plat
                        Nomor Kendaraan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Dikembalikan</th>
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
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4">Pengembalian Kendaraan</h2>
            <!-- Form Inputs -->
            <form id="dataForm" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="applicantId" name="application_id" value="">

                <div class="flex flex-col items-center w-fit gap-4">
                    <span>Punya Foto Struk BBM?</span>
                    <div class="flex justify-center gap-8 mb-8">
                        <div class="flex flex-col items-center">
                            <input id="radio-no" type="radio" name="claim_bbm" value="false"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="radio-no">Tidak</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <input checked id="radio-yes" type="radio" name="claim_bbm" value="true"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="radio-yes">Ya</label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="fuel_used" class="block text-gray-700">Lampirkan Struk BBM</label>
                    <input class="border shadow-sm rounded-md py-2 px-3" id="photo_receipt" name="photo_receipt"
                        type="file" accept=".jpg, .jpeg, .png, .HEIC">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="fuel_used" class="block text-gray-700">Jumlah BBM (Liter)</label>
                    <input type="number" id="fuel_used" name="fuel_used" class="border shadow-sm rounded-md py-2 px-3">
                </div>

                <div class="flex flex-col w-full mb-2">
                    <label for="receipt_amount" class="block text-gray-700">Jumlah Uang BBM (Rp)</label>
                    <input type="number" id="receipt_amount" name="receipt_amount"
                        class="border shadow-sm rounded-md py-2 px-3">
                </div>
                <!-- Modal Buttons -->
                <div class="flex justify-end">
                    <button id="closeModal" type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2 mr-2">Batal</button>
                    <button type="button" id="save" class="bg-blue-500 text-white rounded-lg px-4 py-2">Laporkan
                        Sekarang</button>
                </div>
            </form>
        </div>
    </div>
    <div id="dataModalInfo" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <!-- Modal Content -->
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4"><span id="detailStatus"></span></h2>
            <div class="absolute z-0 right-4 top-4">
                <i id="iconStatus" class="fa fa-3x" aria-hidden="true"></i>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="flex flex-col items-center w-full mb-2">
                    <label for="imgReceipt" class="block text-gray-700">Foto Bon BBM</label>
                    <a id="linkImage" href="" target="_blank">
                        <img id="imgReceipt" src="" alt="Image Receipt" class="w-56 object-contain">
                    </a>
                </div>
                <div class="flex flex-col w-full mb-2">
                    <label for="fuelUsed" class="block text-gray-700">Jumlah Penggunaan BBM (Liter)</label>
                    <input type="text" disabled id="fuelUsed" name="fuelUsed"
                        class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex flex-col w-full mb-2">
                    <label for="receiptAmount" class="block text-gray-700">Jumlah Uang BBM (Rp)</label>
                    <input type="text" disabled id="receiptAmount" name="receiptAmount"
                        class="border shadow-sm rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="button" id="closeModalInfo" class="bg-blue-500 text-white rounded-lg px-4 py-2 mt-4 mr-2">OK</button>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('#dataTables').DataTable({
                ajax: '{!! route('applicant.return.datatable') !!}',
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
                        data: 'merkPlatNumber',
                        name: 'merkPlatNumber'
                    },
                    {
                        data: 'returnAt',
                        name: 'returnAt'
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
            const fetchUrl = '{{ route('applicant.return.needreport') }}';

            function fetchData() {
                $.ajax({
                    url: fetchUrl,
                    type: 'GET',
                    success: function(data) {
                        if (!data.data) {
                            $("#addNewDataButton").attr('disabled', true);
                        } else {
                            $("#applicantId").val(data.data.uuid);
                            $("#addNewDataButton").attr('disabled', false);
                        }
                    },
                    error: function(err) {
                        console.error('Error fetching data:', err);
                    }
                });
            }
            fetchData();

            $('#addNewDataButton').click(function() {
                $('#dataModal').removeClass('hidden');
                $('#dataForm')[0].reset();
            });

            $(document).ready(function() {
                function toggleInputs() {
                    if ($('#radio-yes').is(':checked')) {
                        $('#photo_receipt, #fuel_used, #receipt_amount').prop('disabled', false);
                    } else {
                        $('#photo_receipt, #fuel_used, #receipt_amount').prop('disabled', true);
                    }
                }
                toggleInputs();
                $('input[name="claim_bbm"]').on('change', function() {
                    toggleInputs();
                });
            });

            $('#closeModal').click(function() {
                $('#dataModal').addClass('hidden');
            });

            $('#closeModalInfo').click(function() {
                $("#iconStatus").removeClass(
                    'fa-check-circle text-green-500 fa-exclamation-circle text-red-500 fa-clock-o text-yellow-500 fa-car text-blue-500 fa-ban text-red-500'
                );
                $('#dataModalInfo').addClass('hidden');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#save').click(async function(e) {
                e.preventDefault();
                $(this).prop('disabled', true).html(
                    '<span class="mr-2">Laporkan Sekarang</span><i class="fa fa-spinner fa-pulse fa-fw"></i>'
                );

                const formData = new FormData($('#dataForm')[0]);

                const fileInput = document.getElementById('photo_receipt');
                const file = fileInput.files[0];

                if (file) {
                    try {
                        const compressedFile = await compressImage(file);

                        formData.delete('photo_receipt');
                        formData.append('photo_receipt', compressedFile, compressedFile.name);
                    } catch (error) {
                        console.error('Error compressing image:', error);
                    }
                }

                $.ajax({
                    url: '{{ route('applicant.return.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        success(res.message);
                        $('#dataModal').addClass('hidden');
                        $('#dataTables').DataTable().ajax.reload();
                        $('#dataForm')[0].reset();
                        fetchData();
                    },
                    error: function(err) {
                        handleError(err);
                    },
                    complete: function() {
                        $('#save').prop('disabled', false).text('Laporkan Sekarang');
                    }
                });
            });

            async function compressImage(file, maxWidth = 800, maxHeight = 800, quality = 0.8) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();

                    reader.onload = (event) => {
                        const img = new Image();
                        img.src = event.target.result;

                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            let {
                                width,
                                height
                            } = img;

                            if (width > height && width > maxWidth) {
                                height = Math.round((height *= maxWidth / width));
                                width = maxWidth;
                            } else if (height > maxHeight) {
                                width = Math.round((width *= maxHeight / height));
                                height = maxHeight;
                            }

                            canvas.width = width;
                            canvas.height = height;

                            ctx.drawImage(img, 0, 0, width, height);

                            canvas.toBlob(
                                (blob) => {
                                    if (blob) {
                                        const compressedFile = new File([blob], file.name, {
                                            type: blob.type,
                                            lastModified: Date.now(),
                                        });
                                        resolve(compressedFile);
                                    } else {
                                        reject(new Error('Image compression failed.'));
                                    }
                                },
                                'image/jpeg',
                                quality
                            );
                        };

                        img.onerror = (err) => reject(err);
                    };

                    reader.onerror = (err) => reject(err);
                    reader.readAsDataURL(file);
                });
            }

            $('table#dataTables tbody').on('click', 'td button', function(e) {
                const action = $(this).attr("data-mode");
                const data = $('#dataTables').DataTable().row($(this).parents('tr')).data();

                if (action === 'detail') {
                    popModalInfo(data)
                    $('#dataModalInfo').removeClass('hidden');
                }
            });

            function popModalInfo(data) {
                $("#imgReceipt").attr('src', data.photoReceipt);
                $("#linkImage").attr('href', data.photoReceipt);
                $("#fuelUsed").val(data.fuel_used + ' Liter');
                $("#receiptAmount").val(data.receiptAmount);
                $("#detailStatus").text(data.status === 'returned' ? 'Kendaraan Sudah di Kembalikan' : data
                    .status === 'req_claim' ?
                    'Pengajuan Pembayaran BBM' : data.status === 'claimed' ? 'Uang BBM Sudah Diambil' :
                    'Permohonan Claim Ditolak');
                $("#iconStatus").addClass(data.status === 'returned' ? 'fa-car text-blue-500' : data
                    .status === 'req_claim' ? 'fa-clock-o text-yellow-500' : data.status === 'req_claim' ?
                    'fa-exclamation-circle text-red-500' : data.status === 'claimed' ?
                    'fa-check-circle text-green-500' :
                    'fa-ban text-red-500');
            }
            // function popModalInfo(id) {
            //     const data = {
            //         uuid: id
            //     };
            //     $.ajax({
            //         url: "{{ route('applicant.return.cancel') }}",
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
                    url: "{{ route('applicant.return.cancel') }}",
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
