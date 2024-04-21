<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-ticket-alt"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Ticket</div>
  </a>
  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('home') }}">
      <i class="fas fa-home"></i>
      <span>Dashboard</span></a>
  </li>
  <!-- Nav Item - Data Master Menu -->
  
  <!-- Nav Item - Verifikasi -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('petugas') }}">
      <i class="fas fa-clipboard-check"></i>
      <span>Verifikasi</span></a>
  </li>
  <!-- Nav Item - Transaksi -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('transaksi') }}">
      <i class="fas fa-shopping-cart"></i>
      <span>Transaksi</span></a>
  </li>
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">
  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>
</ul>