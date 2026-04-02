
<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.world.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('assets/dist/js/demo.js') }}"></script> --}}



<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script type="text/javascript">
/* function showTime() {
var date = new Date(),
utc = new Date(Date.UTC(
  date.getFullYear(),
  date.getMonth(),
  date.getDate(),
  date.getHours(),
  date.getMinutes(),
  date.getSeconds()
));

document.getElementById('time').innerHTML = utc.toLocaleTimeString();
}

setInterval(showTime, 1000); */
</script>

<script type="text/javascript">
$(function () {
    var storageKey = 'naret-dashboard-theme';
    var toggleButton = document.querySelector('[data-theme-toggle]');
    var toggleLabel = document.querySelector('[data-theme-label]');
    var toggleIcon = document.querySelector('[data-theme-icon]');

    function renderThemeToggle(theme) {
        var isDark = theme === 'dark';

        document.body.classList.toggle('dark-mode', isDark);

        if (!toggleButton || !toggleLabel || !toggleIcon) {
            return;
        }

        toggleButton.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        toggleButton.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        toggleLabel.textContent = isDark ? 'Light mode' : 'Dark mode';
        toggleIcon.innerHTML = isDark
            ? '<i class="fas fa-sun"></i>'
            : '<i class="fas fa-moon"></i>';
    }

    function getSavedTheme() {
        try {
            return localStorage.getItem(storageKey) === 'dark' ? 'dark' : 'light';
        } catch (error) {
            return document.body.classList.contains('dark-mode') ? 'dark' : 'light';
        }
    }

    renderThemeToggle(getSavedTheme());

    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            var nextTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';

            renderThemeToggle(nextTheme);

            try {
                localStorage.setItem(storageKey, nextTheme);
            } catch (error) {}
        });
    }

    $('body.dashboard-modern table td.text-warning, body.dashboard-modern table td.text-info, body.dashboard-modern table td.text-success, body.dashboard-modern table td.text-danger').each(function () {
        var $cell = $(this);

        if ($cell.find('.status-pill').length) {
            return;
        }

        var statusText = $.trim($cell.text());
        if (!statusText) {
            return;
        }

        var pillClass = 'status-pill';

        if ($cell.hasClass('text-warning')) {
            pillClass += ' status-pill--warning';
        } else if ($cell.hasClass('text-info')) {
            pillClass += ' status-pill--info';
        } else if ($cell.hasClass('text-success')) {
            pillClass += ' status-pill--success';
        } else if ($cell.hasClass('text-danger')) {
            pillClass += ' status-pill--danger';
        }

        $cell.empty().append($('<span>', {
            'class': pillClass,
            text: statusText
        }));
    });
});
</script>

