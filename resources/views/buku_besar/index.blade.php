@extends('layouts.master')

@section('title')
    Buku Besar
@endsection

@push('css')
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .header-center {
            text-align: center;
        }

        .header-center select.form-control {
            width: 100%;
            box-sizing: border-box;
        }

        .account-name {
            float: left;
        }

        .account-number {
            float: right;
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Buku Besar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i>
                        Ubah Periode</button>
                    <button onclick="exportPDF()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-excel-o"></i>
                        Export PDF</button>
                </div>
                <div class="box-body table-responsive">
                    @php
                        $bulanIndo = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];

                        $bulanInt = intval($bulan);
                    @endphp
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="header-center" colspan="7">Toko Usaha Baru</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="7">Buku Besar</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="7">Periode {{ $bulanIndo[$bulanInt] ?? '' }}  {{ $tahun }}</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="7">
                                    <select class="form-control" id="filterDropdown" onchange="filterData()">
                                        <option value="kas" class="text-center" selected>Kas</option>
                                        <option value="penjualan" class="text-center">Penjualan</option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="7">
                                    <span class="account-name">Nama Akun: <span id="accountName">Kas</span></span>
                                    <span class="account-number">Nomor Akun: <span id="accountNumber">111</span></span>
                                </th>
                            </tr>
                            <tr>
                                <th class="header-center" rowspan="2">Tanggal</th>
                                <th class="header-center" rowspan="2">Akun</th>
                                <th class="header-center" rowspan="2">Ref</th>
                                <th class="header-center" rowspan="2">Debit</th>
                                <th class="header-center" rowspan="2">Kredit</th>
                                <th class="header-center" colspan="2">Saldo</th>
                            </tr>
                            <tr>
                                <th class="header-center">Debit</th>
                                <th class="header-center">Kredit</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('buku_besar.form')
@endsection

@push('scripts')
    <script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('buku_besar.data', [$bulan, $tahun]) }}',
                },
                columns: [{
                        data: 'tanggal',
                        className: 'header-center',
                    },
                    {
                        data: 'akun',
                        className: 'header-left'
                    },
                    {
                        data: 'ref',
                        className: 'header-center'
                    },
                    {
                        data: 'debit',
                        className: 'text-right'
                    },
                    {
                        data: 'kredit',
                        className: 'text-right'
                    },
                    {
                        data: 'saldo_debit',
                        className: 'text-right'
                    },
                    {
                        data: 'saldo_kredit',
                        className: 'text-right'
                    },
                ],
                dom: 'Brt',
                bSort: false,
                paginate: false,
            });

            $('.datepicker').datepicker({
                format: "mm-yyyy",
                minViewMode: "months",
                autoclose: true
            });

            table.ajax.url('{{ route('buku_besar.data', [$bulan, $tahun]) }}?filter=' + $('#filterDropdown').val()).load();
        });

        function filterData() {
            const selectedFilter = $('#filterDropdown').val();
            let accountName = 'Kas';
            let accountNumber = '111';

            if (selectedFilter === 'penjualan') {
                accountName = 'Penjualan';
                accountNumber = '411';
            }

            $('#accountName').text(accountName);
            $('#accountNumber').text(accountNumber);

            table.ajax.url('{{ route('buku_besar.data', [$bulan, $tahun]) }}?filter=' + selectedFilter).load();
        }

        function updatePeriode() {
            $('#modal-form').modal('show');
        }

        function exportPDF() {
            const filter = $('#filterDropdown').val();
            const url = '{{ route('buku_besar.export_pdf', [$bulan, $tahun]) }}?filter=' + filter;
            window.open(url, '_blank');
        }
    </script>
@endpush
