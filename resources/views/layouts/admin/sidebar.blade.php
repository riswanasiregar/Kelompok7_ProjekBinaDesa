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
      <a href="{{ route('warga.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-group-line"></i>
        <div data-i18n="Warga">Warga</div>
      </a>
    </li>

    <!-- Users -->
    <li class="menu-item">
      <a href="{{ route('users.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-user-settings-line"></i>
        <div data-i18n="Users">Users</div>
      </a>
    </li>

    <!-- Program Bantuan -->
    <li class="menu-item">
      <a href="{{ route('program_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-hand-heart-line"></i>
        <div data-i18n="Program Bantuan">Program Bantuan</div>
      </a>
    </li>

    <!-- Pendaftar Bantuan -->
    <li class="menu-item">
      <a href="{{ route('pendaftar_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-survey-line"></i>
        <div data-i18n="Pendaftar Bantuan">Pendaftar Bantuan</div>
      </a>
    </li>

    <!-- Verifikasi Lapangan -->
    <li class="menu-item">
      <a href="{{ route('verifikasi_lapangan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-check-double-line"></i>
        <div data-i18n="Verifikasi Lapangan">Verifikasi Lapangan</div>
      </a>
    </li>

    <!-- Penerima Bantuan -->
    <li class="menu-item">
      <a href="{{ route('penerima_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-user-received-line"></i>
        <div data-i18n="Penerima Bantuan">Penerima Bantuan</div>
      </a>
    </li>

    <!-- Riwayat Penyaluran Bantuan -->
    <li class="menu-item">
      <a href="{{ route('riwayat_penyaluran_bantuan.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-history-line"></i>
        <div data-i18n="Riwayat Penyaluran Bantuan">Riwayat Penyaluran Bantuan</div>
      </a>
    </li>

    <!-- Apps & Pages -->
    <li class="menu-header mt-7">
      <span class="menu-header-text">Apps &amp; Pages</span>
    </li>

    <!-- Account Settings -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ri ri-layout-left-line"></i>
        <div data-i18n="Account Settings">Account Settings</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="pages-account-settings-account.html" class="menu-link">
            <div data-i18n="Account">Account</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="pages-account-settings-notifications.html" class="menu-link">
            <div data-i18n="Notifications">Notifications</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="pages-account-settings-connections.html" class="menu-link">
            <div data-i18n="Connections">Connections</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Authentications -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ri ri-shield-keyhole-line"></i>
        <div data-i18n="Authentications">Authentications</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{route('login') }}" class="menu-link" target="_blank">
            <div data-i18n="Basic">Login</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="auth-register-basic.html" class="menu-link" target="_blank">
            <div data-i18n="Basic">Register</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="auth-forgot-password-basic.html" class="menu-link" target="_blank">
            <div data-i18n="Basic">Forgot Password</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Misc -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ri ri-box-3-line"></i>
        <div data-i18n="Misc">Misc</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="pages-misc-error.html" class="menu-link" target="_blank">
            <div data-i18n="Error">Error</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="pages-misc-under-maintenance.html" class="menu-link" target="_blank">
            <div data-i18n="Under Maintenance">Under Maintenance</div>
          </a>
        </li>
      </ul>
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
