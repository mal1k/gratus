<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
<script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>

<title>@yield('title')</title>

<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        <span class="fs-4">Gratus. Organization admin</span>
      </a>

      <ul class="nav nav-pills">
        <li class="nav-item"><a href="{{ route('organization.dashboard', $org->slug) }}" class="nav-link @if(app()->view->getSections()['title'] == 'Dashboard') active @endif" aria-current="page">Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('organizationgroups.index', $org->slug) }}" class="nav-link @if(app()->view->getSections()['title'] == 'Groups') active @endif">Groups</a></li>
        <li class="nav-item"><a href="{{ route('organizationreceivers.index', $org->slug) }}" class="nav-link @if(app()->view->getSections()['title'] == 'Receivers') active @endif">Receivers</a></li>
        <li class="nav-item"><a href="{{ route('organizationtippers.index', $org->slug) }}" class="nav-link @if(app()->view->getSections()['title'] == 'Tippers') active @endif">Tippers</a></li>
        <li class="nav-item">
          <form method=POST action=/organization/logout>
            @csrf
            <button type="submit">logout</button>
          </form>
        </li>
      </ul>
    </header>
  </div>

@yield('content')
