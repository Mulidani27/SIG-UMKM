<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Landing Page</title>
    <!-- ==== STYLE.CSS ==== -->
    <link rel="stylesheet" href="{{ asset('css/styleland.css') }}" />

    <!-- ====  REMIXICON CDN ==== -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- ==== ANIMATE ON SCROLL CSS CDN ==== -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>
<body>
    <!-- ==== HEADER ==== -->
    <header class="container header">
        <!-- ==== NAVBAR ==== -->
        <nav class="nav">
            <div class="logo">
                <a href="{{ route('landing') }}">
                    <h2>SIG UMKM</h2>
                </a>
            </div>

            <div class="nav_menu" id="nav_menu">
                <button class="close_btn" id="close_btn">
                    <i class="ri-close-fill"></i>
                </button>

                <ul class="nav_menu_list">
                    <li class="nav_menu_item">
                        <a href="#" class="nav_menu_link">Home</a>
                    </li>
                    <li class="nav_menu_item">
                        <a href="#" class="nav_menu_link">Peta</a>
                    </li>
                    <li class="nav_menu_item">
                        <a href="#" class="nav_menu_link">Data UMKM</a>
                    </li>
                    <li class="nav_menu_item">
                        <a href="#" class="nav_menu_link">Kontak</a>
                    </li>
                    <li class="nav_menu_item">
                        <a href="{{ route('dashboard') }}" class="nav_menu_link">Masuk</a>
                    </li>
                </ul>
            </div>

            <button class="toggle_btn" id="toggle_btn">
                <i class="ri-menu-line"></i>
            </button>
        </nav>
    </header>

    <!-- ==== MAIN SECTION ==== -->
    <section class="wrapper">
        <div class="container">
            <div class="grid-cols-2">
                <div class="grid-item-1">
                    <h1 class="main-heading">
                        Selamat Datang di <br />
                        <span>SIG UMKM</span>
                    </h1>
                    <p class="info-text">
                        Build a beautiful, modern website with flexible components built from scratch.
                    </p>

                    <div class="btn_wrapper">
                        <button class="btn view_more_btn">
                            Lihat Pemetaan <i class="ri-arrow-right-line"></i>
                        </button>
                        <button class="btn documentation_btn">Data UMKM</button>
                    </div>
                </div>
                <div class="grid-item-2">
                    <div class="team_img_wrapper">
                        <img src="{{ asset('template/assets/compiled/png/landing.png') }}" alt="mapping-img" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==== FEATURE SECTION ==== -->
    <section class="wrapper">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="grid-cols-3">
                <div class="grid-col-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="featured_info">
                        <span class="title">Built for developers</span>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                            ratione facilis animi voluptas exercitationem molestiae.
                        </p>
                    </div>
                </div>

                <div class="grid-col-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="featured_info">
                        <span class="title">Designed to be modern</span>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut
                            ipsum esse corrupti. Quo, labore debitis!
                        </p>
                    </div>
                </div>

                <div class="grid-col-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <div class="featured_info">
                        <span class="title">Documentation for everything</span>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Non
                            nostrum voluptate totam ipsa corrupti vero!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==== INCLUDE SCRIPTS ==== -->
    @include('include.script')
    @include('include.footer')
</body>
</html>
