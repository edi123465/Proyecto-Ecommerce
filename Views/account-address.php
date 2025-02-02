<?php
session_start();
// Verificar si hay una alerta de bienvenida para mostrar
if (isset($_SESSION['show_welcome_alert']) && $_SESSION['show_welcome_alert'] === true) {
    if ($_SESSION['user_name'] === 'Invitado') {
        echo "<script>alert('¡Bienvenido, Invitado!');</script>";
    } else {
        echo "<script>alert('¡Bienvenido, " . $_SESSION['user_name'] . "!');</script>";
    }

    // Limpiar la alerta después de mostrarla
    unset($_SESSION['show_welcome_alert']);
}
?>
<!DOCTYPE html>
<html lang="en">


    <!-- Mirrored from freshcart.codescandy.com/pages/account-address.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:04 GMT -->
    <head>

        <title>FreshCart - eCommerce HTML Template</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="FreshCart is a beautiful eCommerce HTML template specially designed for multipurpose shops & online stores selling products. Most Loved by Developers to build a store website easily.">
        <meta content="Codescandy" name="author" />


        <!-- Favicon icon-->
        <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon/favicon.ico">



        <!-- Libs CSS -->
        <link href="../assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="../assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet" />
        <link href="../assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
        <link href="../assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
        <link href="../assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />
        <link href="../assets/libs/nouislider/dist/nouislider.min.css" rel="stylesheet">
        <link href="../assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">
        <link href="../assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />
        <link href="../assets/libs/prismjs/themes/prism-okaidia.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="../assets/css/theme.min.css">
    </head>

    <body>

        <!-- navigation -->
        <div class="border-bottom pb-5">
            <div class="bg-light py-1">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-12"><span> Super Value Deals - Save more with coupons</span></div>
                        <div class="col-6 text-end d-none d-md-block">
                            <div class="dropdown">
                                <a class="dropdown-toggle text-decoration-none  text-muted" href="#" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="me-1">


                                        <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#selectedlang)">
                                        <path d="M0 0.5H16V12.5H0V0.5Z" fill="#012169" />
                                        <path
                                            d="M1.875 0.5L7.975 5.025L14.05 0.5H16V2.05L10 6.525L16 10.975V12.5H14L8 8.025L2.025 12.5H0V11L5.975 6.55L0 2.1V0.5H1.875Z"
                                            fill="white" />
                                        <path
                                            d="M10.6 7.525L16 11.5V12.5L9.225 7.525H10.6ZM6 8.025L6.15 8.9L1.35 12.5H0L6 8.025ZM16 0.5V0.575L9.775 5.275L9.825 4.175L14.75 0.5H16ZM0 0.5L5.975 4.9H4.475L0 1.55V0.5Z"
                                            fill="#C8102E" />
                                        <path d="M6.025 0.5V12.5H10.025V0.5H6.025ZM0 4.5V8.5H16V4.5H0Z" fill="white" />
                                        <path d="M0 5.325V7.725H16V5.325H0ZM6.825 0.5V12.5H9.225V0.5H6.825Z" fill="#C8102E" />
                                        </g>
                                        <defs>
                                        <clipPath id="selectedlang">
                                            <rect width="16" height="12" fill="white" transform="translate(0 0.5)" />
                                        </clipPath>
                                        </defs>
                                        </svg>


                                    </span>English
                                </a>

                                <ul class="dropdown-menu" >
                                    <li><a class="dropdown-item " href="#"><span class="me-2">


                                                <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_5543_19736)">
                                                <path d="M0 0.5H16V12.5H0V0.5Z" fill="#012169" />
                                                <path
                                                    d="M1.875 0.5L7.975 5.025L14.05 0.5H16V2.05L10 6.525L16 10.975V12.5H14L8 8.025L2.025 12.5H0V11L5.975 6.55L0 2.1V0.5H1.875Z"
                                                    fill="white" />
                                                <path
                                                    d="M10.6 7.525L16 11.5V12.5L9.225 7.525H10.6ZM6 8.025L6.15 8.9L1.35 12.5H0L6 8.025ZM16 0.5V0.575L9.775 5.275L9.825 4.175L14.75 0.5H16ZM0 0.5L5.975 4.9H4.475L0 1.55V0.5Z"
                                                    fill="#C8102E" />
                                                <path d="M6.025 0.5V12.5H10.025V0.5H6.025ZM0 4.5V8.5H16V4.5H0Z" fill="white" />
                                                <path d="M0 5.325V7.725H16V5.325H0ZM6.825 0.5V12.5H9.225V0.5H6.825Z" fill="#C8102E" />
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_5543_19736">
                                                    <rect width="16" height="12" fill="white" transform="translate(0 0.5)" />
                                                </clipPath>
                                                </defs>
                                                </svg>


                                            </span>English</a></li>
                                    <li><a class="dropdown-item " href="#"><span class="me-2">

                                                <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_5543_19744)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.5H16V12.5H0V0.5Z" fill="white" />
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.5H5.3325V12.5H0V0.5Z" fill="#002654" />
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.668 0.5H16.0005V12.5H10.668V0.5Z"
                                                      fill="#CE1126" />
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_5543_19744">
                                                    <rect width="16" height="12" fill="white" transform="translate(0 0.5)" />
                                                </clipPath>
                                                </defs>
                                                </svg>

                                            </span>Français</a></li>
                                    <li><a class="dropdown-item " href="#"><span class="me-2">

                                                <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_5543_19751)">
                                                <path d="M0 8.5H16V12.5H0V8.5Z" fill="#FFCE00" />
                                                <path d="M0 0.5H16V4.5H0V0.5Z" fill="black" />
                                                <path d="M0 4.5H16V8.5H0V4.5Z" fill="#DD0000" />
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_5543_19751">
                                                    <rect width="16" height="12" fill="white" transform="translate(0 0.5)" />
                                                </clipPath>
                                                </defs>
                                                </svg>

                                            </span>Deutsch</a></li>




                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="navbar navbar-light py-lg-5 pt-3 px-0 pb-0">
                <div class="container">
                    <div class="row w-100 align-items-center g-3">
                        <div class="col-xxl-2 col-lg-3">
                            <a class="navbar-brand d-none d-lg-block" href="../index.html">
                                <img src="../assets/images/logo/freshcart-logo.svg" alt="eCommerce HTML Template">

                            </a>
                            <div class="d-flex justify-content-between w-100 d-lg-none">
                                <a class="navbar-brand" href="#">
                                    <img src="../assets/images/logo/freshcart-logo.svg" alt="eCommerce HTML Template">

                                </a>

                                <div class="d-flex align-items-center lh-1">

                                    <div class="list-inline me-2">
                                        <div class="list-inline-item">

                                            <a href="#!" class="text-muted" data-bs-toggle="modal" data-bs-target="#userModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-user">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="list-inline-item">

                                            <a class="text-muted" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                               href="#offcanvasExample" role="button" aria-controls="offcanvasRight">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-shopping-bag">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                                                </svg>
                                            </a>
                                        </div>

                                    </div>
                                    <!-- Button -->
                                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                        <span class="icon-bar top-bar mt-0"></span>
                                        <span class="icon-bar middle-bar"></span>
                                        <span class="icon-bar bottom-bar"></span>
                                    </button>

                                </div>
                            </div>

                        </div>
                        <div class="col-xxl-6 col-lg-5 d-none d-lg-block">

                            <form action="#" class="search-header">


                                <div class="input-group">
                                    <input type="text" class="form-control border-end-0" placeholder="Search for products..."
                                           aria-label="Search for products..." aria-describedby="basic-addon2">
                                    <span class="input-group-text bg-transparent" id="basic-addon2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg></span>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2 col-xxl-3 d-none d-lg-block">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn  btn-outline-gray-400 text-muted" data-bs-toggle="modal"
                                    data-bs-target="#locationModal">
                                <i class="feather-icon icon-map-pin me-2"></i>Location
                            </button>


                        </div>
                        <div class="col-md-2 col-xxl-1 text-end d-none d-lg-block">

                            <div class="list-inline">
                                <div class="list-inline-item">

                                    <a href="shop-wishlist.html" class="text-muted position-relative">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-heart">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                        </svg>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                            5
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    </a></div>
                                <div class="list-inline-item">

                                    <a href="#!" class="text-muted" data-bs-toggle="modal" data-bs-target="#userModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </a></div>
                                <div class="list-inline-item">

                                    <a class="text-muted position-relative " data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                       href="#offcanvasExample" role="button" aria-controls="offcanvasRight">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-shopping-bag">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                        </svg>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                            1
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    </a>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light navbar-default pt-0 pb-0">
                <div class="container px-0 px-md-3">

                    <div class="dropdown me-3 d-none d-lg-block">
                        <button class="btn btn-primary px-6 " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <span class="me-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-grid">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                                </svg></span> All Departments
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="shop-grid.html">Dairy, Bread & Eggs</a></li>
                            <li><a class="dropdown-item" href="shop-grid.html">Snacks & Munchies</a></li>
                            <li><a class="dropdown-item" href="shop-grid.html">Fruits & Vegetables</a></li>
                            <li><a class="dropdown-item" href="shop-grid.html">Cold Drinks & Juices</a></li>
                            <li><a class="dropdown-item" href="shop-grid.html">Breakfast & Instant Food</a></li>
                            <li><a class="dropdown-item" href="shop-grid.html">Bakery & Biscuits</a></li>

                            <li><a class="dropdown-item" href="shop-grid.html">Chicken, Meat & Fish</a></li>
                        </ul>
                    </div>



                    <div class="offcanvas offcanvas-start p-4 p-lg-0" id="navbar-default">

                        <div class="d-flex justify-content-between align-items-center mb-2 d-block d-lg-none">
                            <div><img src="../assets/images/logo/freshcart-logo.svg" alt="eCommerce HTML Template"></div>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="d-block d-lg-none mb-2 pt-2">
                            <a class="btn btn-primary w-100 d-flex justify-content-center align-items-center" data-bs-toggle="collapse"
                               href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <span class="me-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-grid">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg></span> All Departments
                            </a>
                            <div class="collapse mt-2" id="collapseExample">
                                <div class="card card-body">
                                    <ul class="mb-0 list-unstyled">
                                        <li><a class="dropdown-item" href="shop-grid.html">Dairy, Bread & Eggs</a></li>
                                        <li><a class="dropdown-item" href="shop-grid.html">Snacks & Munchies</a></li>
                                        <li><a class="dropdown-item" href="shop-grid.html">Fruits & Vegetables</a></li>
                                        <li><a class="dropdown-item" href="shop-grid.html">Cold Drinks & Juices</a></li>
                                        <li><a class="dropdown-item" href="shop-grid.html">Breakfast & Instant Food</a></li>
                                        <li><a class="dropdown-item" href="shop-grid.html">Bakery & Biscuits</a></li>

                                        <li><a class="dropdown-item" href="shop-grid.html">Chicken, Meat & Fish</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-lg-none d-block mb-3">
                            <button type="button" class="btn  btn-outline-gray-400 text-muted w-100 " data-bs-toggle="modal"
                                    data-bs-target="#locationModal">
                                <i class="feather-icon icon-map-pin me-2"></i>Pick Location
                            </button>
                        </div>
                        <div class="d-none d-lg-block">
                            <ul class="navbar-nav ">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Home
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="../index.php">Home 1</a></li>
                                        <li><a class="dropdown-item" href="index-2.html">Home 2</a></li>
                                        <li><a class="dropdown-item" href="index-3.html">Home 3</a></li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Shop
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="shop-grid.php">Shop Grid - Filter</a></li>
                                        <li><a class="dropdown-item" href="shop-grid-3-column.html">Shop Grid - 3 column</a>
                                        </li>
                                        <li><a class="dropdown-item" href="shop-list.html">Shop List - Filter</a></li>
                                        <li><a class="dropdown-item" href="shop-filter.html">Shop - Filter</a></li>
                                        <li><a class="dropdown-item" href="shop-fullwidth.html">Shop Wide</a></li>
                                        <li><a class="dropdown-item" href="shop-single.html">Shop Single</a></li>
                                        <li><a class="dropdown-item" href="shop-wishlist.html">Shop Wishlist</a></li>
                                        <li><a class="dropdown-item" href="shop-cart.html">Shop Cart</a></li>
                                        <li><a class="dropdown-item" href="shop-checkout.html">Shop Checkout</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Stores
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="store-list.html">Store List</a></li>
                                        <li><a class="dropdown-item" href="store-grid.html">Store Grid</a></li>
                                        <li><a class="dropdown-item" href="store-single.html">Store Single</a></li>

                                    </ul>
                                </li>

                                <li class="nav-item dropdown dropdown-fullwidth">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Mega menu
                                    </a>
                                    <div class=" dropdown-menu pb-0">

                                        <div class="row p-2 p-lg-4">
                                            <div class="col-lg-3 col-6 mb-4 mb-lg-0">
                                                <h6 class="text-primary ps-3">Dairy, Bread & Eggs</h6>
                                                <a class="dropdown-item" href="shop-grid.html">Butter</a>
                                                <a class="dropdown-item" href="shop-grid.html">Milk Drinks</a>
                                                <a class="dropdown-item" href="shop-grid.html">Curd & Yogurt</a>
                                                <a class="dropdown-item" href="shop-grid.html">Eggs</a>
                                                <a class="dropdown-item" href="shop-grid.html">Buns & Bakery</a>
                                                <a class="dropdown-item" href="shop-grid.html">Cheese</a>
                                                <a class="dropdown-item" href="shop-grid.html">Condensed Milk</a>
                                                <a class="dropdown-item" href="shop-grid.html">Dairy Products</a>

                                            </div>
                                            <div class="col-lg-3 col-6 mb-4 mb-lg-0">
                                                <h6 class="text-primary ps-3">Breakfast & Instant Food</h6>
                                                <a class="dropdown-item" href="shop-grid.html">Breakfast Cereal</a>
                                                <a class="dropdown-item" href="shop-grid.html"> Noodles, Pasta & Soup</a>
                                                <a class="dropdown-item" href="shop-grid.html">Frozen Veg Snacks</a>
                                                <a class="dropdown-item" href="shop-grid.html"> Frozen Non-Veg Snacks</a>
                                                <a class="dropdown-item" href="shop-grid.html"> Vermicelli</a>
                                                <a class="dropdown-item" href="shop-grid.html"> Instant Mixes</a>
                                                <a class="dropdown-item" href="shop-grid.html"> Batter</a>
                                                <a class="dropdown-item" href="shop-grid.html"> Fruit and Juices</a>

                                            </div>
                                            <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                <h6 class="text-primary ps-3">Cold Drinks & Juices</h6>
                                                <a class="dropdown-item" href="shop-grid.html">Soft Drinks</a>
                                                <a class="dropdown-item" href="shop-grid.html">Fruit Juices</a>
                                                <a class="dropdown-item" href="shop-grid.html">Coldpress</a>
                                                <a class="dropdown-item" href="shop-grid.html">Water & Ice Cubes</a>
                                                <a class="dropdown-item" href="shop-grid.html">Soda & Mixers</a>
                                                <a class="dropdown-item" href="shop-grid.html">Health Drinks</a>
                                                <a class="dropdown-item" href="shop-grid.html">Herbal Drinks</a>
                                                <a class="dropdown-item" href="shop-grid.html">Milk Drinks</a>


                                            </div>
                                            <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                <div class="card border-0">
                                                    <img src="../assets/images/banner/menu-banner.jpg" alt="eCommerce HTML Template"
                                                         class="img-fluid rounded-3">
                                                    <div class="position-absolute ps-6 mt-8">
                                                        <h5 class=" mb-0 ">Dont miss this <br>offer today.</h5>
                                                        <a href="#" class="btn btn-primary btn-sm mt-3">Shop Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Pages
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="blog.html">Blog</a></li>
                                        <li><a class="dropdown-item" href="blog-single.html">Blog Single</a></li>
                                        <li><a class="dropdown-item" href="blog-category.html">Blog Category</a></li>
                                        <li><a class="dropdown-item" href="about.html">About us</a></li>
                                        <li><a class="dropdown-item" href="404error.html">404 Error</a></li>
                                        <li><a class="dropdown-item" href="contact.html">Contact</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Account
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="signin.html">Sign in</a></li>
                                        <li><a class="dropdown-item" href="signup.html">Signup</a></li>
                                        <li><a class="dropdown-item" href="forgot-password.html">Forgot Password</a></li>
                                        <li class="dropdown-submenu dropend">
                                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                                My Account
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="account-orders.html">Orders</a></li>
                                                <li><a class="dropdown-item" href="account-settings.html">Settings</a></li>
                                                <li><a class="dropdown-item" href="account-address.html">Address</a></li>
                                                <li><a class="dropdown-item" href="account-payment-method.html">Payment Method</a>
                                                </li>
                                                <li><a class="dropdown-item" href="account-notification.html">Notification</a></li>


                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="../docs/index.html">
                                        Docs
                                    </a>

                                </li>
                            </ul>
                        </div>
                        <div class="d-block d-lg-none">
                            <ul class="navbar-nav ">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Home
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="../index.html">Home 1</a></li>
                                        <li><a class="dropdown-item" href="index-2.html">Home 2</a></li>
                                        <li><a class="dropdown-item" href="index-3.html">Home 3</a></li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Shop
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="shop-grid.html">Shop Grid - Filter</a></li>
                                        <li><a class="dropdown-item" href="shop-grid-3-column.html">Shop Grid - 3 column</a>
                                        </li>
                                        <li><a class="dropdown-item" href="shop-list.html">Shop List - Filter</a></li>
                                        <li><a class="dropdown-item" href="shop-filter.html">Shop - Filter</a></li>
                                        <li><a class="dropdown-item" href="shop-fullwidth.html">Shop Wide</a></li>
                                        <li><a class="dropdown-item" href="shop-single.html">Shop Single</a></li>
                                        <li><a class="dropdown-item" href="shop-wishlist.html">Shop Wishlist</a></li>
                                        <li><a class="dropdown-item" href="shop-cart.html">Shop Cart</a></li>
                                        <li><a class="dropdown-item" href="shop-checkout.html">Shop Checkout</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Stores
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="store-list.html">Store List</a></li>
                                        <li><a class="dropdown-item" href="store-grid.html">Store Grid</a></li>
                                        <li><a class="dropdown-item" href="store-single.html">Store Single</a></li>

                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Mega Menu
                                    </a>
                                    <ul class="dropdown-menu">

                                        <li class="dropdown-submenu ">
                                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                                Dairy, Bread & Eggs
                                            </a>
                                            <ul class="dropdown-menu">


                                                <li><a class="dropdown-item" href="shop-grid.html">Milk Drinks</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Curd & Yogurt</a></li>
                                                <li> <a class="dropdown-item" href="shop-grid.html">Eggs</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Buns & Bakery</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Cheese</a></li>
                                                <li> <a class="dropdown-item" href="shop-grid.html">Condensed Milk</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Dairy Products</a></li>


                                            </ul>
                                        </li>
                                        <li class="dropdown-submenu ">
                                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                                Vegetables & Fruits
                                            </a>
                                            <ul class="dropdown-menu">


                                                <li><a class="dropdown-item" href="shop-grid.html">Vegetables</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Fruits</a></li>
                                                <li> <a class="dropdown-item" href="shop-grid.html">Exotics & Premium</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Fresh Sprouts</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Frozen Veg</a></li>



                                            </ul>
                                        </li>
                                        <li class="dropdown-submenu ">
                                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                                Cold Drinks & Juices
                                            </a>
                                            <ul class="dropdown-menu">


                                                <li><a class="dropdown-item" href="shop-grid.html">Soft Drinks</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Fruit Juices</a></li>
                                                <li> <a class="dropdown-item" href="shop-grid.html">Coldpress</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Soda & Mixers</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Milk Drinks</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Health Drinks</a></li>
                                                <li><a class="dropdown-item" href="shop-grid.html">Herbal Drinks</a></li>



                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Pages
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="blog.html">Blog</a></li>
                                        <li><a class="dropdown-item" href="blog-single.html">Blog Single</a></li>
                                        <li><a class="dropdown-item" href="blog-category.html">Blog Category</a></li>
                                        <li><a class="dropdown-item" href="about.html">About us</a></li>
                                        <li><a class="dropdown-item" href="404error.html">404 Error</a></li>
                                        <li><a class="dropdown-item" href="contact.html">Contact</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        Account
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="signin.html">Sign in</a></li>
                                        <li><a class="dropdown-item" href="signup.html">Signup</a></li>
                                        <li><a class="dropdown-item" href="forgot-password.html">Forgot Password</a></li>
                                        <li class="dropdown-submenu dropend">
                                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">
                                                My Account
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="account-orders.html">Orders</a></li>
                                                <li><a class="dropdown-item" href="account-settings.html">Settings</a></li>
                                                <li><a class="dropdown-item" href="account-address.html">Address</a></li>
                                                <li><a class="dropdown-item" href="account-payment-method.html">Payment Method</a>
                                                </li>
                                                <li><a class="dropdown-item" href="account-notification.html">Notification</a></li>


                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="../docs/index.html">
                                        Docs
                                    </a>

                                </li>
                            </ul>
                        </div>


                    </div>
                </div>

            </nav>
        </div>




        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fs-3 fw-bold" id="userModalLabel">Sign Up</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="fullName" placeholder="Enter Your Name" required="">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter Email address" required="">
                            </div>

                            <div class="mb-5">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter Password" required="">
                                <small class="form-text">By Signup, you agree to our <a href="#!">Terms of Service</a> & <a
                                        href="#!">Privacy Policy</a></small>
                            </div>

                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </form>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">

                        Already have an account? <a href="#">Sign in</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Verificar si el usuario está logueado y si es un usuario registrado o invitado
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            // Si está logueado como invitado, mostrar "Invitado"
            if ($_SESSION['user_name'] === 'Invitado') {
                echo "<p>Bienvenido, Invitado!</p>";
            } else {
                // Si está logueado como usuario registrado, mostrar su nombre
                echo "<p>Bienvenido, " . htmlspecialchars($_SESSION['user_name']) . "!</p>";
            }
        } else {
            // Si no está logueado, mostrar una opción para iniciar sesión
            echo "<p>Bienvenido, Invitado!</p>";
        }
        ?>

        <!-- Shop Cart -->

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header border-bottom">
                <div class="text-start">
                    <h5 id="offcanvasRightLabel" class="mb-0 fs-4">Shop Cart</h5>
                    <small>Location in 382480</small>
                </div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                <div class="alert alert-danger" role="alert">
                    You’ve got FREE delivery. Start checkout now!
                </div>

                <div>
                    <div class="py-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item py-3 px-0 border-top">
                                <div class="row align-items-center">
                                    <div class="col-2">
                                        <img src="../assets/images/products/product-img-1.jpg" alt="Ecommerce" class="img-fluid"></div>
                                    <div class="col-5">
                                        <h6 class="mb-0">Organic Banana</h6>
                                        <span><small class="text-muted">.98 / lb</small></span>
                                        <div class="mt-2 small"> <a href="#!" class="text-decoration-none"> <span class="me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></span>Remove</a></div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group  flex-nowrap justify-content-center  ">
                                            <input type="button" value="-"
                                                   class="button-minus form-control  text-center flex-xl-none w-xl-30 w-xxl-10 px-0  "
                                                   data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity"
                                                   class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0 ">
                                            <input type="button" value="+"
                                                   class="button-plus form-control  text-center flex-xl-none w-xl-30  w-xxl-10 px-0  "
                                                   data-field="quantity">
                                        </div>


                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="fw-bold">$35.00</span>

                                    </div>
                                </div>

                            </li>
                            <li class="list-group-item py-3 px-0">
                                <div class="row row align-items-center">
                                    <div class="col-2">
                                        <img src="../assets/images/products/product-img-2.jpg" alt="Ecommerce" class="img-fluid"></div>
                                    <div class="col-5">
                                        <h6 class="mb-0">Fresh Garlic, 250g</h6>
                                        <span><small class="text-muted">250g</small></span>
                                        <div class="mt-2 small"> <a href="#!" class="text-decoration-none"> <span class="me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></span>Remove</a></div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group  flex-nowrap justify-content-center  ">
                                            <input type="button" value="-"
                                                   class="button-minus form-control  text-center flex-xl-none w-xl-30 w-xxl-10 px-0  "
                                                   data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity"
                                                   class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0 ">
                                            <input type="button" value="+"
                                                   class="button-plus form-control  text-center flex-xl-none w-xl-30  w-xxl-10 px-0  "
                                                   data-field="quantity">
                                        </div>


                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="fw-bold">$20.97</span>
                                        <span class="text-decoration-line-through text-muted small">$26.97</span>
                                    </div>
                                </div>

                            </li>
                            <li class="list-group-item py-3 px-0">
                                <div class="row row align-items-center">
                                    <div class="col-2">
                                        <img src="../assets/images/products/product-img-3.jpg" alt="Ecommerce" class="img-fluid"></div>
                                    <div class="col-5">
                                        <h6 class="mb-0">Fresh Onion, 1kg</h6>
                                        <span><small class="text-muted">1 kg</small></span>
                                        <div class="mt-2 small"> <a href="#!" class="text-decoration-none"> <span class="me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></span>Remove</a></div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group  flex-nowrap justify-content-center  ">
                                            <input type="button" value="-"
                                                   class="button-minus form-control  text-center flex-xl-none w-xl-30 w-xxl-10 px-0  "
                                                   data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity"
                                                   class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0 ">
                                            <input type="button" value="+"
                                                   class="button-plus form-control  text-center flex-xl-none w-xl-30  w-xxl-10 px-0  "
                                                   data-field="quantity">
                                        </div>


                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="fw-bold">$25.00</span>
                                        <span class="text-decoration-line-through text-muted small">$45.00</span>
                                    </div>
                                </div>

                            </li>
                            <li class="list-group-item py-3 px-0">
                                <div class="row row align-items-center">
                                    <div class="col-2">
                                        <img src="../assets/images/products/product-img-4.jpg" alt="Ecommerce" class="img-fluid"></div>
                                    <div class="col-5">
                                        <h6 class="mb-0">Fresh Ginger</h6>
                                        <span><small class="text-muted">250g</small></span>
                                        <div class="mt-2 small"> <a href="#!" class="text-decoration-none"> <span class="me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></span>Remove</a></div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group  flex-nowrap justify-content-center  ">
                                            <input type="button" value="-"
                                                   class="button-minus form-control  text-center flex-xl-none w-xl-30 w-xxl-10 px-0  "
                                                   data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity"
                                                   class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0 ">
                                            <input type="button" value="+"
                                                   class="button-plus form-control  text-center flex-xl-none w-xl-30  w-xxl-10 px-0  "
                                                   data-field="quantity">
                                        </div>


                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="fw-bold">$39.87</span>
                                        <span class="text-decoration-line-through text-muted small">$45.00</span>
                                    </div>
                                </div>

                            </li>
                            <li class="list-group-item py-3 px-0 border-bottom">
                                <div class="row row align-items-center">
                                    <div class="col-2">
                                        <img src="../assets/images/products/product-img-5.jpg" alt="Ecommerce" class="img-fluid"></div>
                                    <div class="col-5">
                                        <h6 class="mb-0">Apple Royal Gala, 4 Pieces Box</h6>
                                        <span><small class="text-muted">4 Apple</small></span>
                                        <div class="mt-2 small"> <a href="#!" class="text-decoration-none"> <span class="me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg></span>Remove</a></div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group  flex-nowrap justify-content-center  ">
                                            <input type="button" value="-"
                                                   class="button-minus form-control  text-center flex-xl-none w-xl-30 w-xxl-10 px-0  "
                                                   data-field="quantity">
                                            <input type="number" step="1" max="10" value="1" name="quantity"
                                                   class="quantity-field form-control text-center flex-xl-none w-xl-30 w-xxl-10 px-0 ">
                                            <input type="button" value="+"
                                                   class="button-plus form-control  text-center flex-xl-none w-xl-30  w-xxl-10 px-0  "
                                                   data-field="quantity">
                                        </div>


                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="fw-bold">$39.87</span>
                                        <span class="text-decoration-line-through text-muted small">$45.00</span>
                                    </div>
                                </div>

                            </li>

                        </ul>
                    </div>
                    <div class="d-grid">

                        <button class="btn btn-primary btn-lg d-flex justify-content-between align-items-center" type="submit"> Go to
                            Checkout <span class="fw-bold">$120.00</span></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body p-6">
                        <div class="d-flex justify-content-between align-items-start ">
                            <div>
                                <h5 class="mb-1" id="locationModalLabel">Choose your Delivery Location</h5>
                                <p class="mb-0 small">Enter your address and we will specify the offer you area. </p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="my-5">
                            <input type="search" class="form-control" placeholder="Search your area">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Select Location</h6>
                            <a href="#" class="btn btn-outline-gray-400 text-muted btn-sm">Clear All</a>


                        </div>
                        <div>
                            <div data-simplebar style="height:300px;">
                                <div class="list-group list-group-flush">

                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action active">
                                        <span>Alabama</span><span>Min:$20</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Alaska</span><span>Min:$30</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Arizona</span><span>Min:$50</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>California</span><span>Min:$29</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Colorado</span><span>Min:$80</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Florida</span><span>Min:$90</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Arizona</span><span>Min:$50</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>California</span><span>Min:$29</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Colorado</span><span>Min:$80</span></a>
                                    <a href="#"
                                       class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 list-group-item-action">
                                        <span>Florida</span><span>Min:$90</span></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- section -->
        <section>
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <!-- col -->
                    <div class="col-12">
                        <div class="p-6 d-flex justify-content-between align-items-center d-md-none">
                            <!-- heading -->
                            <h3 class="fs-5 mb-0">Account Setting</h3>
                            <button class="btn btn-outline-gray-400 text-muted d-md-none" type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasAccount" aria-controls="offcanvasAccount">
                                <i class="feather-icon icon-menu"></i>
                            </button>
                        </div>
                    </div>
                    <!-- col -->
                    <div class="col-lg-3 col-md-4 col-12 border-end  d-none d-md-block">
                        <div class="pt-10 pe-lg-10">
                            <!-- nav -->
                            <ul class="nav flex-column nav-pills nav-pills-dark">
                                <!-- nav item -->
                                <li class="nav-item">
                                    <!-- nav link -->
                                    <a class="nav-link " aria-current="page" href="account-orders.html"><i
                                            class="feather-icon icon-shopping-bag me-2"></i>Your Orders</a>
                                </li>
                                <!-- nav item -->
                                <li class="nav-item">
                                    <a class="nav-link " href="account-settings.html"><i
                                            class="feather-icon icon-settings me-2"></i>Settings</a>
                                </li>
                                <!-- nav item -->
                                <li class="nav-item">
                                    <a class="nav-link active" href="account-address.html"><i
                                            class="feather-icon icon-map-pin me-2"></i>Address</a>
                                </li>
                                <!-- nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="account-payment-method.html"><i
                                            class="feather-icon icon-credit-card me-2"></i>Payment Method</a>
                                </li>
                                <!-- nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="account-notification.html"><i
                                            class="feather-icon icon-bell me-2"></i>Notification</a>
                                </li>
                                <!-- nav item -->
                                <li class="nav-item">
                                    <hr>
                                </li>
                                <!-- nav item -->
                                <li class="nav-item">
                                    <a class="nav-link " href="login/logout.php"><i class="feather-icon icon-log-out me-2"></i>Log out</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="p-6 p-lg-10">
                            <div class="d-flex justify-content-between mb-6">
                                <!-- heading -->
                                <h2 class="mb-0">Address</h2>
                                <!-- button -->
                                <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">Add a
                                    new address </a>
                            </div>
                            <div class="row">
                                <!-- col -->
                                <div class="col-lg-5 col-xxl-4 col-12 mb-4">
                                    <!-- form -->
                                    <div class="border p-6 rounded-3">
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="homeRadio" checked>
                                            <label class="form-check-label text-dark fw-semi-bold" for="homeRadio">
                                                Home
                                            </label>
                                        </div>
                                        <!-- address -->
                                        <p class="mb-6">Jitu Chauhan<br>

                                            4450 North Avenue Oakland, <br>

                                            Nebraska, United States,<br>

                                            402-776-1106</p>
                                        <!-- btn -->
                                        <a href="#" class="btn btn-info btn-sm">Default address</a>
                                        <div class="mt-4">
                                            <a href="#" class="text-inherit">Edit </a>
                                            <a href="#" class="text-danger ms-3" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-xxl-4 col-12 mb-4">
                                    <!-- input -->
                                    <div class="border p-6 rounded-3">
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="officeRadio">
                                            <label class="form-check-label text-dark fw-semi-bold" for="officeRadio">
                                                Office
                                            </label>
                                        </div>
                                        <!-- nav item -->
                                        <p class="mb-6">Nitu Chauhan<br>

                                            3853 Coal Road <br>

                                            Tannersville, Pennsylvania, 18372, United States <br>

                                            402-776-1106</p>
                                        <!-- link -->
                                        <a href="#" class="link-primary">Set as Default</a>
                                        <div class="mt-4">
                                            <a href="#" class="text-inherit">Edit </a>
                                            <!-- btn -->
                                            <a href="#" class="text-danger ms-3" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <!-- modal content -->
                <div class="modal-content">
                    <!-- modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- modal body -->
                    <div class="modal-body">
                        <h6>Are you sure you want to delete this address?</h6>
                        <p class="mb-6">Jitu Chauhan<br>

                            4450 North Avenue Oakland, <br>

                            Nebraska, United States,<br>

                            402-776-1106</p>
                    </div>
                    <!-- modal footer -->
                    <div class="modal-footer">
                        <!-- btn -->
                        <button type="button" class="btn btn-outline-gray-400" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <!-- modal content -->
                <div class="modal-content">
                    <!-- modal body -->
                    <div class="modal-body p-6">
                        <div class="d-flex justify-content-between mb-5">
                            <div>
                                <!-- heading -->
                                <h5 class="h6 mb-1" id="addAddressModalLabel">New Shipping Address</h5>
                                <p class="small mb-0">Add new shipping address for your order delivery.</p>
                            </div>
                            <div>
                                <!-- button -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>
                        <!-- row -->
                        <div class="row g-3">
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="First name" aria-label="First name" required="">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="Last name" aria-label="Last name" required="">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="Address Line 1">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="Address Line 2">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="City">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- form select -->
                                <select class="form-select">
                                    <option selected=""> India</option>
                                    <option value="1">UK</option>
                                    <option value="2">USA</option>
                                    <option value="3">UAE</option>
                                </select>
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- form select -->
                                <select class="form-select" aria-label="Default select example">
                                    <option selected="">Gujarat</option>
                                    <option value="1">Northern Ireland</option>
                                    <option value="2"> Alaska</option>
                                    <option value="3">Abu Dhabi</option>
                                </select>
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="Zip Code">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- input -->
                                <input type="text" class="form-control" placeholder="Business Name">
                            </div>
                            <!-- col -->
                            <div class="col-12">
                                <!-- form check -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Set as Default
                                    </label>
                                </div>
                            </div>
                            <!-- col -->
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary" type="button">Save Address</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasAccount" aria-labelledby="offcanvasAccountLabel">
            <!-- offcanvac header -->
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasAccountLabel">Offcanvas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <!-- offcanvac body -->
            <div class="offcanvas-body">
                <!-- nav -->
                <ul class="nav flex-column nav-pills nav-pills-dark">
                    <!-- nav item -->
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="account-orders.html"><i
                                class="feather-icon icon-shopping-bag me-2"></i>Your Orders</a>
                    </li>
                    <!-- nav item -->
                    <li class="nav-item">
                        <a class="nav-link " href="account-settings.html"><i class="feather-icon icon-settings me-2"></i>Settings</a>
                    </li>
                    <!-- nav item -->
                    <li class="nav-item">
                        <a class="nav-link active" href="account-address.html"><i
                                class="feather-icon icon-map-pin me-2"></i>Address</a>
                    </li>
                    <!-- nav item -->
                    <li class="nav-item">
                        <a class="nav-link" href="account-payment-method.html"><i
                                class="feather-icon icon-credit-card me-2"></i>Payment Method</a>
                    </li>
                    <!-- nav item -->
                    <li class="nav-item">
                        <a class="nav-link" href="account-notification.html"><i
                                class="feather-icon icon-bell me-2"></i>Notification</a>
                    </li>
                </ul>
                <hr class="my-6">
                <div>
                    <!-- nav -->
                    <ul class="nav flex-column nav-pills nav-pills-dark">
                        <!-- nav item -->
                        <li class="nav-item">
                            <a class="nav-link " href="../index.html"><i class="feather-icon icon-log-out me-2"></i>Log out</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <!-- footer -->
        <div class="footer">
            <div class="container">
                <footer class="row g-4 py-4">
                    <div class="col-12 col-md-12 col-lg-4">
                        <h6 class="mb-4">Categories</h6>
                        <div class="row">
                            <div class="col-6">
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Vegetables & Fruits</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Breakfast & instant food</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Bakery & Biscuits</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Atta, rice & dal</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Sauces & spreads</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Organic & gourmet</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Baby care</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Cleaning essentials</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Personal care</a></li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Dairy, bread & eggs</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Cold drinks & juices</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Tea, coffee & drinks</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Masala, oil & more</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Chicken, meat & fish</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Paan corner</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link"> Pharma & wellness</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Home & office</a></li>
                                    <li class="nav-item mb-2"><a href="shop-grid.html" class="nav-link">Pet care</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-md-12 col-lg-8">
                        <div class="row g-4">
                            <div class="col-6 col-sm-6 col-md-3">
                                <h6 class="mb-4">Get to know us</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2"><a href="about.html" class="nav-link">Company</a></li>
                                    <li class="nav-item mb-2"><a href="about.html" class="nav-link">About</a></li>
                                    <li class="nav-item mb-2"><a href="blog.html" class="nav-link">Blog</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Help Center</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Our Value</a></li>
                                </ul>
                            </div>

                            <div class="col-6 col-sm-6 col-md-3">
                                <h6 class="mb-4">For Consumers</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Payments</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Shipping</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Product Returns</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">FAQ</a></li>
                                    <li class="nav-item mb-2"><a href="shop-shop-checkout.html" class="nav-link">Shop Checkout</a></li>
                                </ul>
                            </div>

                            <div class="col-6 col-sm-6 col-md-3">
                                <h6 class="mb-4">Become a Shopper</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Shopper Opportunities</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Become a Shopper</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Earnings</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Ideas & Guides</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">New Retailers</a></li>
                                </ul>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3">
                                <h6 class="mb-4">Freshcart programs</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Freshcart programs</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Gift Cards</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Promos & Coupons</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Freshcart Ads</a></li>
                                    <li class="nav-item mb-2"><a href="#!" class="nav-link">Careers</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>





                </footer>
                <div class="border-top py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-5 text-lg-start text-center mb-2 mb-lg-0">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item text-dark">Payment Partners</li>
                                <li class="list-inline-item">
                                    <a href="#!"><img src="../assets/images/payment/amazonpay.svg" alt=""></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#!"><img src="../assets/images/payment/american-express.svg" alt=""></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#!"><img src="../assets/images/payment/mastercard.svg" alt=""></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#!"><img src="../assets/images/payment/paypal.svg" alt=""></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#!"><img src="../assets/images/payment/visa.svg" alt=""></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-7 mt-4 mt-md-0">
                            <ul class="list-inline mb-0 text-lg-end text-center">
                                <li class="list-inline-item mb-2 mb-md-0 text-dark">Get deliveries with FreshCart</li>
                                <li class="list-inline-item ms-4">
                                    <a href="#!"> <img src="../assets/images/appbutton/appstore-btn.svg" alt=""
                                                       style="width: 140px;"></a></li>
                                <li class="list-inline-item">
                                    <a href="#!"> <img src="../assets/images/appbutton/googleplay-btn.svg" alt=""
                                                       style="width: 140px;"></a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="border-top py-4">
                    <div class="row align-items-center">
                        <div class="col-md-6"><span class="small text-muted">Copyright 2023 © FreshCart eCommerce HTML Template.  All rights reserved. Powered by Codescandy.</span></div>
                        <div class="col-md-6">
                            <ul class="list-inline text-md-end mb-0 small mt-3 mt-md-0">
                                <li class="list-inline-item text-muted">Follow us on</li>
                                <li class="list-inline-item me-1">
                                    <a href="#!" class="icon-shape icon-sm social-links"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                                                               class="bi bi-facebook" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg></a></li>
                                <li class="list-inline-item me-1">
                                    <a href="#!" class="icon-shape icon-sm social-links"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                                                               class="bi bi-twitter" viewBox="0 0 16 16">
                                        <path
                                            d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                        </svg></a></li>
                                <li class="list-inline-item">
                                    <a href="#!" class="icon-shape icon-sm social-links"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                                                              class="bi bi-instagram" viewBox="0 0 16 16">
                                        <path
                                            d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                        </svg></a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Javascript-->
        <!-- Libs JS -->
        <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
        <script src="../assets/libs/slick-carousel/slick/slick.min.js"></script>
        <script src="../assets/libs/simplebar/dist/simplebar.min.js"></script>
        <script src="../assets/libs/nouislider/dist/nouislider.min.js"></script>
        <script src="../assets/libs/wnumb/wNumb.min.js"></script>
        <script src="../assets/libs/rater-js/index.js"></script>
        <script src="../assets/libs/prismjs/prism.js"></script>
        <script src="../assets/libs/prismjs/components/prism-scss.min.js"></script>
        <script src="../assets/libs/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
        <script src="../assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
        <script src="../assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
        <script src="../assets/libs/dropzone/dist/min/dropzone.min.js"></script>
        <script src="../assets/libs/flatpickr/dist/flatpickr.min.js"></script>

        <!-- Theme JS -->
        <script src="../assets/js/theme.min.js"></script>
        <!-- choose one -->
    </body>
    <!-- Mirrored from freshcart.codescandy.com/pages/account-address.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Aug 2022 17:47:05 GMT -->
</html>