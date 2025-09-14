<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8" />
        <title>Transport Management System - Company</title>
    <!--FONT AWESOME LINK-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    
        @yield('styles')
        
    </head>

    <body>
        <div class="sidebar">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Company Logo">
            </div>
            <ul class="menu">
                <li>
                    <a href="#">
                        <i class="fa-solid fa-tachograph-digital"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="fa-regular fa-building"></i>
                        <span>Company</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-truck"></i>
                        <span>Driver</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Trips</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History</span>
                    </a>
                </li>
                    <li class="logout">
                    <a href="#">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>

        <!--MAIN CONTENT-->
        <div class="main-content">

            @yield('content')

        </div>
    </body>

    @yield('scripts')
</html>
