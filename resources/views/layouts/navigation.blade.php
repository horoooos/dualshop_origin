<header class="sticky-top">
  <!-- Верхняя панель -->
  <div class="top-bar d-lg-block">
    <div class="container d-flex justify-content-between align-items-center" style="height: 46px">
      @guest
      <div class="d-flex gap-4 text-white">
        <a href="{{ route('login') }}" class="text-white text-decoration-none">Войти</a>
        <a href="{{ route('register') }}" class="text-white text-decoration-none">Зарегистрироваться</a>
      </div>
      @endguest

      @auth
      <div class="d-flex justify-content-between w-100 text-white">
        <!-- Профиль (слева) -->
        <div class="dropdown">
          <a class="text-white text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('user') }}">Профиль</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Выйти</button>
              </form>
            </li>
          </ul>
        </div>

        <!-- Избранное (справа) -->
        <div class="d-flex align-items-center gap-2 ms-auto">
          <i class="bi bi-heart"></i>
          <a href="{{ route('favorites') }}" class="text-white text-decoration-none {{ Request::is('favorites') ? 'active' : '' }}">Избранное</a>
        </div>
      </div>
      @endauth
    </div>
  </div>

  <!-- Основная навигация -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <!-- Логотип и бургер меню -->
      <div class="d-flex align-items-center w-100">
        <button class="navbar-toggler border-0 p-0 me-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="bi bi-list fs-1"></i>
        </button>
        <a class="navbar-brand brand" href="/">dualshop.</a>

        <!-- Основные элементы навигации -->
        <div class="collapse navbar-collapse flex-grow-1" id="navbarNav">
          <div class="d-flex flex-column flex-lg-row align-items-lg-center w-100">
            <!-- Кнопка каталога -->
            <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4 py-2 d-flex align-items-center btn-header me-lg-4 mb-3 mb-lg-0 {{ Request::is('catalog') ? 'active' : '' }}">
              Каталог
              <i class="bi bi-chevron-down ms-2"></i>
            </a>

            <!-- Поиск -->
            <div class="search-box me-lg-4 mb-3 mb-lg-0 flex-grow-1">
              <form action="{{ route('search') }}" method="GET">
                <div class="input-group">
                  <input 
                    type="text" 
                    name="q" 
                    class="form-control border-0" 
                    placeholder="Поиск по сайту"
                  >
                  <button type="submit" class="btn bg-white">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </form>
            </div>

            <!-- Ссылки -->
            <div class="d-flex flex-column flex-lg-row gap-4 me-lg-4 mb-3 mb-lg-0">
              <a href="{{ route('delivery') }}" class="text-dark text-decoration-none {{ Request::is('delivery') ? 'active' : '' }}">Доставка</a>
              <a href="{{ route('shops') }}" class="text-dark text-decoration-none {{ Request::is('shops') ? 'active' : '' }}">Магазины</a>
              <a href="{{ route('promotions') }}" class="text-dark text-decoration-none {{ Request::is('promotions') ? 'active' : '' }}">Акции</a>
            </div>

            <div class="nav-divider me-lg-4 d-none d-lg-block"></div>

            <!-- Корзина -->
            <a href="{{ route('cart') }}" class="text-dark text-decoration-none position-relative {{ Request::is('cart') ? 'active' : '' }}">
              <i class="bi bi-cart3 fs-5 pe-3"></i>
              Корзина
              @auth
              @if(auth()->user()->cartItems()->count() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->cartItems()->count() }}
              </span>
              @endif
              @endauth
            </a>
          </div>
        </div>
      </div>

      <!-- Дублирование профиля для мобильной версии -->
      @auth
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('user') }}">Профиль</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">Выйти</button>
            </form>
          </li>
        </ul>
      </div>
      @endauth
    </div>
  </nav>
</header>
