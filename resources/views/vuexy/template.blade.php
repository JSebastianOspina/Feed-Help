<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <!-- Primary Meta Tags -->
    <title>Feed-Deck | Primer sistema con Inteligencia Artifical</title>
    <meta name="title" content="Feed-Deck | Primer sistema con Inteligencia Artifical">
    <meta name="description"
          content="FeedDeck, es el primer sistema con Inteligencia Artifical desarrollado para la gestion de cuentas de twitter| Actualizando constantemente para mejorar. ">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.feed-help.de/login">
    <meta property="og:title" content="Feed-Deck | Primer sistema con Inteligencia Artificial">
    <meta property="og:description"
          content="FeedDeck, es el primer sistema con Inteligencia Artificial desarrollado para la gestion de cuentas | Actualizando constantemente para mejorar. ">
    <meta property="og:image" content="https://www.feed-help.de/image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.feed-help.de/login">
    <meta property="twitter:title" content="Feed-Deck | Primer sistema con Inteligencia Artificial">
    <meta property="twitter:description"
          content="FeedDeck, es el primer sistema con Inteligencia Artifical desarrollado para la gestion de cuentas | Actualizando constantemente para mejorar. ">
    <meta property="twitter:image" content="https://www.feed-help.de/image.jpg">
    <link rel="apple-touch-icon" href="{{asset('2.0/app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('2.0/app-assets/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
          rel="stylesheet')}}">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-174907300-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-174907300-1');
    </script>


    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/vendors/css/extensions/toastr.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/app-assets/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('2.0/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">

    <link rel="stylesheet" type="text/css"
          href="{{asset('2.0/app-assets/css/plugins/extensions/ext-component-toastr.css')}}">

@yield('head')
<!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('2.0/assets/css/style.css')}}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static  " data-open="click"
      data-menu="vertical-menu-modern" data-col="">


<!-- BEGIN: Header-->
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-dark navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon text-warning"
                                                                                            data-feather="star"></i></a>
                    <div class="bookmark-input search-input">
                        <div class="bookmark-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0"
                               data-search="search">
                        <ul class="search-list search-list-bookmark"></ul>
                    </div>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item dropdown dropdown-language"><a class="nav-link dropdown-toggle" id="dropdown-flag"
                                                               href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                               aria-expanded="false"><i
                        class="flag-icon flag-icon-es"></i><span class="selected-language">Español</span></a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag"><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-language="en"><i
                            class="flag-icon flag-icon-us"></i> English</a></div>
            </li>
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                                                                                         data-feather="sun"></i></a>
            </li>

            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                                                           id="dropdown-user" href="#" data-bs-toggle="dropdown"
                                                           aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name fw-bolder">{{auth()->user()->name}}</span><span
                            class="user-status">User</span></div>
                    <span class="avatar"><img class="round"
                                              src="{{asset('2.0/app-assets/images/avatars/default.png')}}"
                                              alt="avatar" height="40" width="40"><span
                            class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        <button class="dropdown-item" href="auth-login-cover.html">
                            <i class="me-50" data-feather="power"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<ul class="main-search-list-defaultlist d-none">
    <li class="d-flex align-items-center"><a href="#">
            <h6 class="section-label mt-75 mb-0">Files</h6>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="me-75"><img src="{{asset('2.0/app-assets/images/icons/xls.png')}}" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing
                        Manager</small>
                </div>
            </div>
            <small class="search-data-size me-50 text-muted">&apos;17kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="me-75"><img src="{{asset('2.0/app-assets/images/icons/jpg.png')}}" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                        Developer</small>
                </div>
            </div>
            <small class="search-data-size me-50 text-muted">&apos;11kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="me-75"><img src="{{asset('2.0/app-assets/images/icons/pdf.png')}}" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                        Marketing Manager</small>
                </div>
            </div>
            <small class="search-data-size me-50 text-muted">&apos;150kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="me-75"><img src="{{asset('2.0/app-assets/images/icons/doc.png')}}" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                </div>
            </div>
            <small class="search-data-size me-50 text-muted">&apos;256kb</small>
        </a></li>
    <li class="d-flex align-items-center"><a href="#">
            <h6 class="section-label mt-75 mb-0">Members</h6>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view-account.html">
            <div class="d-flex align-items-center">
                <div class="avatar me-75"><img src="{{asset('2.0/app-assets/images/portrait/small/avatar-s-8.jpg')}}"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view-account.html">
            <div class="d-flex align-items-center">
                <div class="avatar me-75"><img src="{{asset('2.0/app-assets/images/portrait/small/avatar-s-1.jpg')}}"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                        Developer</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view-account.html">
            <div class="d-flex align-items-center">
                <div class="avatar me-75"><img src="{{asset('2.0/app-assets/images/portrait/small/avatar-s-14.jpg')}}"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing
                        Manager</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view-account.html">
            <div class="d-flex align-items-center">
                <div class="avatar me-75"><img src="{{asset('2.0/app-assets/images/portrait/small/avatar-s-6.jpg')}}"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                </div>
            </div>
        </a></li>
</ul>
<ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion justify-content-between"><a
            class="d-flex align-items-center justify-content-between w-100 py-50">
            <div class="d-flex justify-content-start"><span class="me-75" data-feather="alert-circle"></span><span>No results found.</span>
            </div>
        </a></li>
</ul>
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand"
                                            href="{{asset('2.0/html/ltr/vertical-menu-template-dark/index.html')}}"><span
                        class="brand-logo">
                            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                                    y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%"
                                                    x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path"
                                                  d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                                  style="fill:currentColor"></path>
                                            <path id="Path1"
                                                  d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                                  fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                                     points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                                     points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                                     points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg></span>
                    <h2 class="brand-text">FeedDeck 2.0</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0"
                                               data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                        data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item {{ Route::currentRouteName() === 'news.index' ? 'active' : ''}}"><a
                    class="d-flex align-items-center" href="{{route('news.index')}}"><i
                        data-feather="home"></i><span
                        class="menu-title text-truncate" data-i18n="Email">Inicio </span></a>
            </li>

            <li class=" navigation-header"><span>Funciones del Deck</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-more-horizontal">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
            </li>

            <li class=" nav-item {{ Route::currentRouteName() === 'decks.index' ? 'active' : ''}}"><a
                    class="d-flex align-items-center" href="{{route('decks.index')}}"><i
                        data-feather="twitter"></i><span class="menu-title text-truncate"
                                                         data-i18n="Chat">Decks</span></a>
            </li>


        </ul>
    </div>
</div>
<!-- END: Main Menu-->


<!-- BEGIN: Content-->
<div class="app-content content ">

    @yield('content','content')
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a
                class="ms-25" href="https://1.envato.market/pixinvent_portfolio" target="_blank">FeedDeck</a><span
                class="d-none d-sm-inline-block">, All rights Reserved</span></span><span
            class="float-md-end d-none d-md-block">Hand-crafted & Made with <i data-feather="heart"></i></span></p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->


<!-- BEGIN: Vendor JS-->
<script src="{{asset('2.0/app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('2.0/app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{asset('2.0/app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('2.0/app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->


<script>
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
@if ($errors->any())

    @foreach ($errors->all() as $error)
        <script>
            toastr['error']('{{$error}}', 'Algo salió mal', {
                closeButton: true,
                timeOut: 0,
                tapToDismiss: false,
            });
        </script>
    @endforeach
@endif

@if(\Illuminate\Support\Facades\Session::has('success'))
    <script>
        toastr['success']('{!! session()->get('success') !!}', '¡Exito!', {
            closeButton: true,
            timeOut: 0,
            tapToDismiss: false,
        });
    </script>
@endif
@if(\Illuminate\Support\Facades\Session::has('error'))
    <script>
        toastr['error']('{!! session()->get('error') !!}', 'Algo salió mal', {
            closeButton: true,
            timeOut: 0,
            tapToDismiss: false,
        });
    </script>
@endif
</body>
<!-- END: Body-->

</html>
