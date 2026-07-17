<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '/img/user.jpg') }}" class="img-circle img-profil"
                    alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-sitemap"></i>
                        <span>Master Data</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('coa.index') }}"><i class="fa fa-circle-o"></i> Coa</a></li>
                        <li><a href="{{ route('kategori.index') }}"><i class="fa fa-circle-o"></i> Kategori</a></li>
                        <li><a href="{{ route('produk.index') }}"><i class="fa fa-circle-o"></i> Produk</a></li>
                        <li><a href="{{ route('member.index') }}"><i class="fa fa-circle-o"></i> Member</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-tasks"></i>
                        <span>Transaksi</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('transaksi.baru') }}"><i class="fa fa-circle-o"></i> Penjualan</a>
                        </li>
                        <li><a href="{{ route('penjualan.index') }}"><i class="fa fa-circle-o"></i> Daftar Penjualan</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file"></i>
                        <span>Report</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('laporan.index') }}"><i class="fa fa-circle-o"></i> Laporan Penjualan</a>
                        </li>
                        <li><a href="{{ route('jurnal_umum.index') }}"><i class="fa fa-circle-o"></i> Jurnal Umum</a>
                        </li>
                        <li><a href="{{ route('buku_besar.index') }}"><i class="fa fa-circle-o"></i> Buku Besar</a>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span>Setting</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('user.index') }}"><i class="fa fa-circle-o"></i> User</a></li>
                        <li><a href="{{ route('setting.index') }}"><i class="fa fa-circle-o"></i> Pengaturan</a></li>
                    </ul>
                </li>
            @else
                <li class="header">TRANSAKSI</li>
                <li>
                    <a href="{{ route('transaksi.baru') }}">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                    </a>
                </li>
            @endif
    </section>
    <!-- /.sidebar -->
</aside>
