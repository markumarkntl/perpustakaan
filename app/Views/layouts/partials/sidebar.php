                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard') ?>" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub <?= ($activeMenu ?? '') === 'master' ? 'active' : '' ?>">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-stack"></i>
                                <span>Data Master</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item <?= ($activeSub ?? '') === 'kategori-buku' ? 'active' : '' ?>">
                                    <a href="<?= base_url('kategori-buku') ?>" class="submenu-link">Kategori Buku</a>
                                </li>
                                <li class="submenu-item <?= ($activeSub ?? '') === 'katalog-buku' ? 'active' : '' ?>">
                                    <a href="<?= base_url('katalog-buku') ?>" class="submenu-link">Katalog Buku</a>
                                </li>
                                <li class="submenu-item <?= ($activeSub ?? '') === 'data-anggota' ? 'active' : '' ?>">
                                    <a href="<?= base_url('data-anggota') ?>" class="submenu-link">Data Anggota</a>
                                </li>
                               <li class="submenu-item <?= ($activeSub ?? '') === 'petugas' ? 'active' : '' ?>">
                                    <a href="<?= base_url('petugas') ?>" class="submenu-link">Data Petugas</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item has-sub <?= ($activeMenu ?? '') === 'transaksi' ? 'active' : '' ?>">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-collection-fill"></i>
                                <span>Transaksi</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="#" class="submenu-link">Peminjaman Buku</a></li>
                                <li class="submenu-item"><a href="#" class="submenu-link">Pengembalian Buku</a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item has-sub <?= ($activeMenu ?? '') === 'laporan' ? 'active' : '' ?>">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Laporan</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="#" class="submenu-link">Riwayat Pinjaman</a></li>
                                <li class="submenu-item"><a href="#" class="submenu-link">Laporan Denda</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /sidebar -->