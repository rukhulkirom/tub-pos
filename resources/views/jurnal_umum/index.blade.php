@extends('layouts.master')

@section('title')
    Jurnal Umum
@endsection

@push('css')
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .text-right {
            text-align: right;
        }

        .header-center {
            text-align: center;
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Jurnal Umum</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i>
                        Ubah Periode</button>
                    <a href="{{ route('jurnal_umum.export_pdf', [$bulan, $tahun]) }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="header-center" colspan="5">Toko Usaha Baru</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="5">Jurnal Umum</th>
                            </tr>
                            <tr>
                                <th class="header-center" colspan="5">Periode {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</th>
                            </tr>
                            <tr>
                                <th class="header-center">Tanggal</th>
                                <th class="header-center">Akun</th>
                                <th class="header-center">Ref</th>
                                <th class="header-center">Debit</th>
                                <th class="header-center">Kredit</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('jurnal_umum.form')
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
                    url: '{{ route('jurnal_umum.data', [$bulan, $tahun]) }}',
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
