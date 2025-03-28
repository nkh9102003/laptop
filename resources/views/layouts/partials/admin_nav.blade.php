<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <button id="toggle-sidebar" class="btn btn-link me-2 d-flex align-items-center" >
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="{{ route('admin.reports.index') }}">
            <i class="fas fa-laptop me-2"></i>{{ __('messages.welcome') }} Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Management links moved to sidebar -->
            </ul>
            <ul class="navbar-nav">
                <!-- Language Switcher -->
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-globe me-1"></i> {{ __('messages.language') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                                <img src="https://flagcdn.com/w20/us.png" alt="English" class="me-2" width="20"> {{ __('messages.english') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}" href="{{ route('language.switch', 'vi') }}">
                                <img src="https://flagcdn.com/w20/vn.png" alt="Vietnamese" class="me-2" width="20"> {{ __('messages.vietnamese') }}
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-1"></i> {{ __('messages.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav> 