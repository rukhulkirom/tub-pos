@extends('layouts.master')

@section('title')
    Laporan Penjualan
@endsection

@push('css')
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .header-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Laporan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i>
                        Ubah Periode</button>
                    <a href="{{ route('laporan.export_pdf', [$bulan, $tahun]) }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <tr>
                                <th class="header-center" colspan="8">Toko Usaha Baru</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="8">Laporan Penjualan Bulanan</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="8">Periode {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</th>
                            </tr>
                            <tr>
                                <th class="header-center">Tanggal</th>
                                <th style="text-align: center;">Nama Member</th>
                                <th style="text-align: center;">Nama Produk</th>
                                <th style="text-align: center;"">Harga Jual</th>
                                <th class="header-center" width="15%">Jumlah</th>
                                <th class="header-center" width="15%">Diskon</th>
                                <th style="text-align: center;">Subtotal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('laporan.form')
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
                    url: '{{ route('laporan.data', [$bulan, $tahun]) }}',
                },
                columns: [
                    {
                        data: 'tanggal',
                        className: 'header-center'
                    },
                    {
                        data: 'nama',
                        className: 'header-center'
                    },
                    {
                        data: 'nama_produk',
                    },
                    {
                        data: 'harga_jual',
                        className: 'text-right'
                    },
                    {
                        data: 'jumlah',
                        className: 'header-center'
                    },
                    {
                        data: 'diskon',
                        className: 'header-center'
                    },
                    {
                        data: 'penjualan',
                        className: 'text-right'
                    }
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false,
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        function updatePeriode() {
            $('#modal-form').modal('show');
        }
    </script>
@endpush
