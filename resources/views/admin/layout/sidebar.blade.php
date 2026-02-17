   <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="bi bi-code-square"></i>
                </div>
                <span class="logo-text">ProgMaker</span>
            </div>
            <button id="sidebarToggle" class="sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <ul class="sidebar-nav">
                <li class="nav-section">
                    <span class="section-title">الرئيسية</span>
                </li>
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span class="nav-text">لوحة التحكم</span>
                    </a>
                </li>

                <li class="nav-section mt-3">
                    <span class="section-title">الإعدادات الأساسية</span>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.grades.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.grades.index') }}" class="nav-link">
                        <i class="bi bi-grid"></i>
                        <span class="nav-text">المراحل الدراسية</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subjects.index') }}" class="nav-link">
                        <i class="bi bi-book"></i>
                        <span class="nav-text">المواد</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.groups.index') }}" class="nav-link">
                        <i class="bi bi-people"></i>
                        <span class="nav-text">المجموعات</span>
                    </a>
                </li>

                <li class="nav-section mt-3">
                    <span class="section-title">الطلاب</span>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.students.index') }}" class="nav-link">
                        <i class="bi bi-person-badge"></i>
                        <span class="nav-text">الطلاب</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="User" class="user-avatar">
                <div class="user-info">
                    <span class="user-name">المسؤول</span>
                    <span class="user-role">مدير النظام</span>
                </div>
            </div>
        </div>
    </nav>
