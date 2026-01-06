<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- LOGO -->
  <div class="app-brand demo pt-4 pb-3">
    <div class="app-brand-container d-flex flex-column align-items-center">
      <a href="{{ route('admin.dashboard') }}" class="app-brand-link mb-2">
        <div class="app-brand-logo d-flex justify-content-center align-items-center">
          <img
            src="{{ asset('assets-admin/img/logo/bansos.png') }}"
            alt="Logo Bantuan Sosial"
            class="d-block"
            style="height: 130px; width: auto; max-width: 100%;"
          >
        </div>
      </a>
    </div>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-none d-xl-flex">
      <i class="menu-toggle-icon d-xl-inline-block align-middle ri-menu-fold-line"></i>
    </a>
  </div>
  <!-- /LOGO -->

  <div class="border-top mx-3 my-2"></div>

  <ul class="menu-inner py-1">
    <div class="menu-inner-shadow"></div>

    <!-- Dashboards -->
    <li class="menu-item">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-home-smile-line"></i>
        <div data-i18n="Dashboards">Dashboards</div>
      </a>
    </li>

    <!-- Warga -->
    <li class="menu-item">
      <a href="{{ route('admin.warga.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-group-line"></i>
        <div data-i18n="Warga">Warga</div>
      </a>
    </li>

    <!-- Users -->
    <li class="menu-item">
      <a href="{{ route('admin.users.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-user-settings-line"></i>
        <div data-i18n="Users">Users</div>
      </a>
    </li>

    <!-- Program Bantuan -->
    <li class="menu-item">
      <a href="{{ route('admin.program_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-hand-heart-line"></i>
        <div data-i18n="Program Bantuan">Program Bantuan</div>
      </a>
    </li>

    <!-- Pendaftar Bantuan -->
    <li class="menu-item">
      <a href="{{ route('admin.pendaftar_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-survey-line"></i>
        <div data-i18n="Pendaftar Bantuan">Pendaftar Bantuan</div>
      </a>
    </li>

    <!-- Verifikasi Lapangan -->
    <li class="menu-item">
      <a href="{{ route('admin.verifikasi_lapangan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-check-double-line"></i>
        <div data-i18n="Verifikasi Lapangan">Verifikasi Lapangan</div>
      </a>
    </li>

    <!-- Penerima Bantuan -->
    <li class="menu-item">
      <a href="{{ route('admin.penerima_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-user-received-line"></i>
        <div data-i18n="Penerima Bantuan">Penerima Bantuan</div>
      </a>
    </li>

    <!-- Riwayat Penyaluran Bantuan -->
    <li class="menu-item">
      <a href="{{ route('admin.riwayat_penyaluran_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-history-line"></i>
        <div data-i18n="Riwayat Penyaluran Bantuan">Riwayat Penyaluran Bantuan</div>
      </a>
    </li>



  </ul>
</aside>

<style>
/* Styling untuk logo agar tidak terpotong */
.app-brand {
  position: relative;
  min-height: 180px;
}

.app-brand-container {
  width: 100%;
}

.app-brand-logo {
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
}

.app-brand-logo img {
  object-fit: contain;
  max-height: 100%;
  max-width: 100%;
}

/* Hover effect */
.app-brand-logo img:hover {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 1199.98px) {
  .app-brand-logo {
    height: 120px;
  }

  .app-brand {
    min-height: 160px;
  }
}

@media (max-width: 767.98px) {
  .app-brand-logo {
    height: 100px;
  }

  .app-brand {
    min-height: 140px;
  }
}
</style>
