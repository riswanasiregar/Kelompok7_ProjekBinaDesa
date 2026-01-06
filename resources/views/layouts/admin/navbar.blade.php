<nav
  class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
  id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base ri ri-menu-line icon-md"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        <i class="icon-base ri ri-search-line icon-lg lh-0"></i>
        <input
          type="text"
          class="form-control border-0 shadow-none"
          placeholder="Search..."
          aria-label="Search..." />
      </div>
    </div>
    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-md-auto">
      <!-- Place this tag where you want the button to render. -->
      <li class="nav-item lh-1 me-4">
        <a
          class="github-button"
          href="https://github.com/themeselection/materio-bootstrap-html-admin-template-free"
          data-icon="octicon-star"
          data-size="large"
          data-show-count="true"
          aria-label="Star themeselection/materio-html-admin-template-free on GitHub"
          >Star</a
        >
      </li>

      <!-- Auth Check: Tampilkan User dropdown jika sudah login, tombol Login jika belum -->
      @if(Auth::check())
        <!-- User dropdown (hanya tampil jika user sudah login) -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a
            class="nav-link dropdown-toggle hide-arrow p-0"
            href="javascript:void(0);"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            role="button">
            <div class="avatar avatar-online">
              <img src="{{ asset('assets-admin/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="#">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('assets-admin/img/avatars/1.png') }}" alt="Avatar" class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-0">{{ Auth::user()->name ?? 'User' }}</h6>
                    <small class="text-body-secondary">{{ Auth::user()->role ?? 'Admin' }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider my-1"></div>
            </li>
            <li>
              <a class="dropdown-item" href="#">
                <i class="icon-base ri ri-time-line icon-md me-3"></i>
                <span>
                  @if(session('last_login'))
                    {{ \Carbon\Carbon::parse(session('last_login'))->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                  @else
                    First time login
                  @endif
                </span>
              </a>
            </li>

            <li>
              <div class="dropdown-divider my-1"></div>
            </li>
           <li>
  <div class="d-grid px-4 pt-2 pb-1">
    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-danger d-flex">
        <small class="align-middle">Logout</small>
        <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
      </button>
    </form>
  </div>
</li>
          </ul>
        </li>
        <!--/ User -->
      @else
        <!-- Tombol Login (hanya tampil jika user BELUM login) -->
        <li class="nav-item">
          <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
        </li>
      @endif
    </ul>
  </div>
</nav>

<!-- Script untuk memastikan dropdown berfungsi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Pastikan Bootstrap Dropdown diinisialisasi
  var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));

  if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
    // Jika Bootstrap sudah dimuat, inisialisasi dropdown
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl);
    });
    console.log('Bootstrap Dropdown initialized:', dropdownList.length);
  } else {
    // Fallback: Manual toggle jika Bootstrap belum dimuat
    console.warn('Bootstrap JS tidak ditemukan, menggunakan fallback manual');

    dropdownElementList.forEach(function(toggle) {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var dropdown = this.nextElementSibling;

        // Tutup semua dropdown lain
        document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
          if (menu !== dropdown) {
            menu.classList.remove('show');
          }
        });

        // Toggle dropdown saat ini
        dropdown.classList.toggle('show');
      });
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
          menu.classList.remove('show');
        });
      }
    });
  }
});
</script>
