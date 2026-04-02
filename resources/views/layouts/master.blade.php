<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @include('includes.head')
  </head>

  <body class="hold-transition skin-blue sidebar-mini layout-fixed dashboard-modern">
    <script>
      (function () {
        try {
          if (localStorage.getItem('naret-dashboard-theme') === 'dark') {
            document.body.classList.add('dark-mode');
          }
        } catch (error) {}
      })();
    </script>
    <div class="wrapper">

      <nav class="main-header navbar navbar-expand-md navbar-light navbar-info">
        @include('includes.header')
      </nav>


        @include('includes.sidebar')

      <div class="content-wrapper">
        <section class="content">
          @yield('content')
        </section>
      </div>

      @include('includes.footer_scripts')
      @yield('pagescripts')
    </div>
  </body>
</html>
