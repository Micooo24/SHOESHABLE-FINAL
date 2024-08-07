<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
<nav id="fixed-sidebar" class="sidebar" >
    <header>
        <div class="image-text text-center">
            <span class="image">
                <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('photo/logo.png') }}" alt="Profile Image" style="width: 80px; height: 80px; border-radius: 50%;">
            </span>
            <div class="header-text" style="font-size: 22px;">
                <span class="name">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>
    <div class="menu-bar">
        <div class="menu">
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="/profile" class="nav-link-item">
                        <i class='bx bx-user icon'></i>
                        <span class="text nav-text">Account</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/shop" class="nav-link-item" style="font-size: 16px;">
                        <i class='bx bx-store icon'></i>
                        <span class="text nav-text">Shop</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/carts"  class="nav-link-item">
                        <i class='bx bx-cart icon'></i>
                        <span class="text nav-text">Cart</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/user/purchase" class="nav-link-item">
                        <i class='bx bx-receipt icon'></i>
                        <span class="text nav-text">My Purchase</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/my-wishlist" class="nav-link-item" class="wishlist">
                        <i class='fa-regular fa-heart icon'></i>
                        <span class="text nav-text">Wishlists</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/settings" class="nav-link-item">
                        <i class='bx bx-cog icon'></i>
                        <span class="text nav-text">Settings</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/help" class="nav-link-item">
                        <i class='bx bx-question-mark icon'></i>
                        <span class="text nav-text">Help</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="bottom-content">
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="#" id="logoutButton" class="nav-link-item">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
