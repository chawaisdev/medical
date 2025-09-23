        <!-- POPPER JS -->
        <script src="{{ asset('assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>

        <!-- BOOTSTRAP JS -->
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- NODE WAVES JS -->
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/js/index.js') }}"></script>

        <!-- SIMPLEBAR JS -->
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <link rel="modulepreload" href="{{ asset('assets/simplebar-635bad04.js') }}" />
        <script type="module" src="{{ asset('assets/simplebar-635bad04.js') }}"></script>

        <!-- COLOR PICKER JS -->
        <script src="{{ asset('assets/libs/%40simonwep/pickr/pickr.es5.min.js') }}"></script>

        <!-- JSVECTOR MAPS JS -->
        <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

        <!-- APEX CHARTS JS -->
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- CHARTJS CHART JS -->
        <script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>

        <!-- CRM-Dashboard -->
        <link rel="modulepreload" href="{{ asset('assets/crm-dashboard-5975eef2.js') }}" />
        <script type="module" src="{{ asset('assets/crm-dashboard-5975eef2.js') }}"></script>


        <!-- STICKY JS -->
        <script src="{{ asset('assets/sticky.js') }}"></script>

        <!-- APP JS -->
        <link rel="modulepreload" href="{{ asset('assets/app-3cade095.js') }}" />
        <script type="module" src="{{ asset('assets/app-3cade095.js') }}"></script>

        <!-- CUSTOM-SWITCHER JS -->
        <link rel="modulepreload" href="{{ asset('assets/custom-switcher-383b6a5b.js') }}" />
        <script type="module" src="{{ asset('assets/custom-switcher-383b6a5b.js') }}"></script>

        <!-- Include jQuery and Bootstrap JS -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- END SCRIPTS -->
        <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script> -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>


        <!-- Include DataTables JS-->
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap4.js"></script>

        <!-- jQuery (required by Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    placeholder: $(this).data('placeholder'),
                    allowClear: true
                });
            });
        </script>

        <script>
            new DataTable('#example');
        </script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    allowClear: true,
                    width: '100%' // ensure full width
                });
            });
        </script>
        @yield('js')
