<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ URL::asset('resources/css/organization.css') }}">

<script   src="https://code.jquery.com/jquery-3.6.0.js"   integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="   crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
<script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>

<title>@yield('title')</title>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">{{ $org->name }}</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
          <form method=POST class="nav-link px-3" action="/organization/{{ $org->slug }}/logout">
            @csrf
            <button type="submit" class="hide-btn_style text-muted"><b>Logout</b></button>
          </form>
      </div>
    </div>
  </header>
  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-low_dark">
        <div class="position-sticky pt-3">

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-1 mt-4 mb-1 text-muted">
            <span>Select category</span>
            <!-- <a class="link-secondary" href="#" aria-label="Add a new report">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            </a> -->
          </h6>
          <ul class="nav flex-column">
            <li class="nav-item active">
              <a class="nav-link text-nav_light" aria-current="page" href="{{ route('organization.dashboard', $org->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-nav_light" href="{{ route('organizationgroups.index', $org->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file" aria-hidden="true"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                Groups
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-nav_light" href="{{ route('organizationreceivers.index', $org->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                Receivers
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-nav_light" href="{{ route('organizationtippers.index', $org->slug) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Tippers
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-nav_light" href="{{ route('organizationtransactions.index', $org->slug) }}">
                  <!-- svg -->
                  Transactions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-nav_light" href="{{ route('organizationschedule.index', $org->slug) }}">
                  <!-- svg -->
                  Shifts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-nav_light" href="{{ route('organizationsnfcaccess.index', $org->slug) }}">
                  <!-- svg -->
                  NFC Access
                </a>
            </li>
          </ul>

        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">@yield('title')</h1>
          <!-- <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <span class="breadcrumb-item active">@yield('title')</span>
            </div>
          </div> -->
        </div>

        @yield('content')
      </main>
    </div>
  </div>
