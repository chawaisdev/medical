<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="dark" data-toggled="close">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{ asset('assets/images/getwell.png') }}">
    <!-- TITLE -->
    <title> @yield('title')</title>
    @include('includes.style')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        table.dataTable th.dt-type-numeric,
        table.dataTable th.dt-type-date,
        table.dataTable td.dt-type-numeric,
        table.dataTable td.dt-type-date {
            text-align: left;
        }
    </style>
</head>

<body>
    <!-- LOADER -->
    <div id="loader">
        <img src="/assets/images/media/loader.svg" alt="">
    </div>
    <!-- END LOADER -->

    <!-- PAGE -->
    <div class="page">

        @include('includes.header')
        @include('includes.sidebar')

        <!-- MAIN-CONTENT -->

        <div class="main-content app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert customize-alert alert-dismissible text-success alert-light-success bg-success-subtle fade show remove-close-icon mt-3"
                        role="alert">
                        <div class="d-flex align-items-center  me-3 me-md-0">
                            <i class="ti ti-info-circle fs-5 me-2 text-success"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert customize-alert mt-3 alert-dismissible alert-light-danger bg-danger-subtle text-danger fade show remove-close-icon"
                        role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <div class="d-flex align-items-center  me-3 me-md-0">
                            <i class="ti ti-info-circle fs-5 me-2 text-danger"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('body')

            </div>
        </div>
        <!-- END MAIN-CONTENT -->

        <!-- SEARCH-MODAL -->


        <!-- END SEARCH-MODAL -->

        <!-- FOOTER -->
        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a href="javascript:void(0);"
                        class="text-dark fw-semibold">GetWell</a>
            </div>
        </footer>
        <!-- END FOOTER -->

    </div>
    <!-- END PAGE-->

    <!-- SCRIPTS -->

    <!-- SCROLL-TO-TOP -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    @include('includes.script')
</body>

</html>
