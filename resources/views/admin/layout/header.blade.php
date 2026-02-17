        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button id="mobileToggle" class="mobile-toggle d-lg-none">
                    <i class="bi bi-list"></i>
                </button>
                <div class="search-box d-none d-md-flex">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="بحث...">
                </div>
            </div>
            
            <div class="navbar-right">
                <div class="nav-item dropdown">
                    <button class="nav-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">5</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                        <div class="dropdown-header">
                            <span>الإشعارات</span>
                            <a href="#" class="mark-all-read">تحديد الكل كمقروء</a>
                        </div>
                        <div class="notifications-list">
                            <a href="#" class="notification-item unread">
                                <div class="notification-icon bg-primary">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <div class="notification-content">
                                    <p>مستخدم جديد تم تسجيله</p>
                                    <span class="time">منذ 5 دقائق</span>
                                </div>
                            </a>
                            <a href="#" class="notification-item unread">
                                <div class="notification-icon bg-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <p>تم اكتمال الطلب #1234</p>
                                    <span class="time">منذ 15 دقيقة</span>
                                </div>
                            </a>
                            <a href="#" class="notification-item">
                                <div class="notification-icon bg-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="notification-content">
                                    <p>تنبيه: مخزون منخفض</p>
                                    <span class="time">منذ ساعة</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown-footer">
                            <a href="#">عرض الكل</a>
                        </div>
                    </div>
                </div>
                
                <div class="nav-item dropdown">
                    <button class="nav-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-envelope"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end messages-dropdown">
                        <div class="dropdown-header">
                            <span>الرسائل</span>
                            <a href="#" class="mark-all-read">تحديد الكل كمقروء</a>
                        </div>
                        <div class="messages-list">
                            <a href="#" class="message-item unread">
                                <img src="https://ui-avatars.com/api/?name=Ahmed+Mohamed&background=random" alt="">
                                <div class="message-content">
                                    <h6>أحمد محمد</h6>
                                    <p>مرحباً، هل يمكنك مساعدتي في...</p>
                                    <span class="time">منذ 10 دقائق</span>
                                </div>
                            </a>
                            <a href="#" class="message-item unread">
                                <img src="https://ui-avatars.com/api/?name=Sara+Ali&background=random" alt="">
                                <div class="message-content">
                                    <h6>سارة علي</h6>
                                    <p>شكراً لك على المساعدة!</p>
                                    <span class="time">منذ 30 دقيقة</span>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown-footer">
                            <a href="#">عرض الكل</a>
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
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="User">
                        <span class="d-none d-md-inline">المسؤول</span>
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
                        <a href="#" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i>
                            تسجيل الخروج
                        </a>
                    </div>
                </div>
            </div>
        </nav>
