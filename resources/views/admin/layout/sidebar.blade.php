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
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link" data-tooltip="لوحة التحكم">
                        <i class="bi bi-speedometer2 text-primary"></i>
                        <span class="nav-text">لوحة التحكم</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.grades.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.grades.index') }}" class="nav-link" data-tooltip="المراحل الدراسية">
                        <i class="bi bi-grid text-info"></i>
                        <span class="nav-text">المراحل الدراسية</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subjects.index') }}" class="nav-link" data-tooltip="المواد">
                        <i class="bi bi-book text-warning"></i>
                        <span class="nav-text">المواد</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.groups.index') }}" class="nav-link" data-tooltip="المجموعات">
                        <i class="bi bi-people text-success"></i>
                        <span class="nav-text">المجموعات</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.students.index') }}" class="nav-link" data-tooltip="الطلاب">
                        <i class="bi bi-person-badge text-primary"></i>
                        <span class="nav-text">الطلاب</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.teachers.index') }}" class="nav-link" data-tooltip="المدرسون">
                        <i class="bi bi-person-video3 text-info"></i>
                        <span class="nav-text">المدرسون</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.schedule.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.schedule.index') }}" class="nav-link" data-tooltip="جدول الحصص">
                        <i class="bi bi-calendar-week text-warning"></i>
                        <span class="nav-text">جدول الحصص</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.attendance.index') }}" class="nav-link" data-tooltip="الحضور والغياب">
                        <i class="bi bi-clipboard-check text-success"></i>
                        <span class="nav-text">الحضور والغياب</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.homework.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.homework.index') }}" class="nav-link" data-tooltip="الواجبات">
                        <i class="bi bi-journal-check text-primary"></i>
                        <span class="nav-text">الواجبات</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.exams.index') }}" class="nav-link" data-tooltip="الامتحانات">
                        <i class="bi bi-award text-info"></i>
                        <span class="nav-text">الامتحانات</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscriptions.index') }}" class="nav-link" data-tooltip="الاشتراكات والمدفوعات">
                        <i class="bi bi-cash-stack text-success"></i>
                        <span class="nav-text">الاشتراكات والمدفوعات</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.subscriptions.reports.monthly') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscriptions.reports.monthly') }}" class="nav-link" data-tooltip="تقرير الدخل الشهري">
                        <i class="bi bi-graph-up text-primary"></i>
                        <span class="nav-text">تقرير الدخل الشهري</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.subscriptions.reports.teacher_commission') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscriptions.reports.teacher_commission') }}" class="nav-link" data-tooltip="تقرير عمولات المدرسين">
                        <i class="bi bi-percent text-warning"></i>
                        <span class="nav-text">تقرير عمولات المدرسين</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.expenses.index') }}" class="nav-link" data-tooltip="المصروفات">
                        <i class="bi bi-receipt text-danger"></i>
                        <span class="nav-text">المصروفات</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.communication.absence') ? 'active' : '' }}">
                    <a href="{{ route('admin.communication.absence') }}" class="nav-link" data-tooltip="إشعارات الغياب">
                        <i class="bi bi-bell text-primary"></i>
                        <span class="nav-text">إشعارات الغياب</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.communication.exams') ? 'active' : '' }}">
                    <a href="{{ route('admin.communication.exams') }}" class="nav-link" data-tooltip="إشعارات الامتحانات">
                        <i class="bi bi-bell-fill text-info"></i>
                        <span class="nav-text">إشعارات الامتحانات</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.communication.broadcast') ? 'active' : '' }}">
                    <a href="{{ route('admin.communication.broadcast') }}" class="nav-link" data-tooltip="الرسائل الجماعية">
                        <i class="bi bi-megaphone text-warning"></i>
                        <span class="nav-text">الرسائل الجماعية</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer py-2">
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="User" class="user-avatar">
                <div class="user-info">
                    <span class="user-name">المسؤول</span>
                    <span class="user-role">مدير النظام</span>
                </div>
            </div>
        </div>
    </nav>
