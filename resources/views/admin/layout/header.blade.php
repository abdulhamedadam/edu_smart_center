        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button id="mobileToggle" class="mobile-toggle d-lg-none">
                    <i class="bi bi-list"></i>
                </button>
                <form method="GET" action="{{ route('admin.students.index') }}" class="search-box d-none d-md-flex">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="بحث عن طالب بالاسم أو الكود"
                    >
                </form>
            </div>
            
            <div class="navbar-right">
                <div class="nav-item dropdown">
                    <button class="nav-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        @if(($headerNotificationsCount ?? 0) > 0)
                            <span class="notification-badge">{{ $headerNotificationsCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                        <div class="dropdown-header">
                            <span>الإشعارات</span>
                        </div>
                        <div class="notifications-list">
                            @forelse($headerNotifications ?? [] as $notification)
                                @php
                                    $icon = 'bi-bell';
                                    $bg = 'bg-primary';
                                    if ($notification->type === 'absence') {
                                        $icon = 'bi-person-dash';
                                        $bg = 'bg-danger';
                                    } elseif ($notification->type === 'exam') {
                                        $icon = 'bi-award';
                                        $bg = 'bg-info';
                                    } elseif ($notification->type === 'broadcast') {
                                        $icon = 'bi-megaphone';
                                        $bg = 'bg-success';
                                    }

                                    $who = $notification->student?->name
                                        ?? $notification->parent?->name
                                        ?? '';
                                @endphp
                                <div class="notification-item">
                                    <div class="notification-icon {{ $bg }}">
                                        <i class="bi {{ $icon }}"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="mb-0">
                                            @if($who)
                                                <strong>{{ $who }}:</strong>
                                            @endif
                                            {{ \Illuminate\Support\Str::limit($notification->title ?: $notification->body, 60) }}
                                        </p>
                                        <span class="time">
                                            {{ $notification->sent_at?->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted py-3 mb-0">
                                    لا توجد إشعارات حالياً
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="nav-item dropdown">
                    <button class="nav-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-envelope"></i>
                        @if(($headerMessagesCount ?? 0) > 0)
                            <span class="notification-badge">{{ $headerMessagesCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end messages-dropdown">
                        <div class="dropdown-header">
                            <span>الرسائل</span>
                        </div>
                        <div class="messages-list">
                            @forelse($headerMessages ?? [] as $message)
                                @php
                                    $name = $message->parent?->name
                                        ?? $message->student?->name
                                        ?? $message->title;
                                @endphp
                                <div class="message-item">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=random" alt="">
                                    <div class="message-content">
                                        <h6>{{ $name }}</h6>
                                        <p>{{ \Illuminate\Support\Str::limit($message->body, 70) }}</p>
                                        <span class="time">
                                            {{ $message->sent_at?->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted py-3 mb-0">
                                    لا توجد رسائل حالياً
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="nav-item dropdown">
                    <button class="nav-btn theme-toggle" id="themeToggle">
                        <i class="bi bi-moon"></i>
                    </button>
                </div>
                
                <div class="nav-item dropdown user-dropdown">
                    <button class="user-btn" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin User') }}&background=6366f1&color=fff" alt="User">
                        <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'المسؤول' }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            الملف الشخصي
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-gear"></i>
                            الإعدادات
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger w-100 text-start">
                                <i class="bi bi-box-arrow-right"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
