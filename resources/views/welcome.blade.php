       @extends("admin.layout.master")
       @section('content')
         <!-- Dashboard Page -->
            <div id="dashboard-page" class="page-content active">
                <div class="page-header">
                    <h1 class="page-title">لوحة التحكم</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">الرئيسية</li>
                        </ol>
                    </nav>
                </div>
                
                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card primary animate__animated animate__fadeInUp">
                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-info">
                                <h3>2,543</h3>
                                <p>إجمالي المستخدمين</p>
                            </div>
                            <div class="stat-chart">
                                <span class="trend up">
                                    <i class="bi bi-arrow-up"></i> 12.5%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card success animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                            <div class="stat-icon">
                                <i class="bi bi-cart3"></i>
                            </div>
                            <div class="stat-info">
                                <h3>1,234</h3>
                                <p>إجمالي الطلبات</p>
                            </div>
                            <div class="stat-chart">
                                <span class="trend up">
                                    <i class="bi bi-arrow-up"></i> 8.2%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card warning animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                            <div class="stat-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="stat-info">
                                <h3>856</h3>
                                <p>المنتجات</p>
                            </div>
                            <div class="stat-chart">
                                <span class="trend down">
                                    <i class="bi bi-arrow-down"></i> 3.1%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card danger animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                            <div class="stat-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="stat-info">
                                <h3>$45,231</h3>
                                <p>إجمالي المبيعات</p>
                            </div>
                            <div class="stat-chart">
                                <span class="trend up">
                                    <i class="bi bi-arrow-up"></i> 18.7%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <div class="card chart-card">
                            <div class="card-header">
                                <h5>إحصائيات المبيعات</h5>
                                <div class="card-actions">
                                    <button class="btn btn-sm btn-outline-primary">يومي</button>
                                    <button class="btn btn-sm btn-primary">أسبوعي</button>
                                    <button class="btn btn-sm btn-outline-primary">شهري</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="salesChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card chart-card">
                            <div class="card-header">
                                <h5>توزيع المستخدمين</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="usersChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header">
                        <h5>أحدث الطلبات</h5>
                        <a href="#orders" class="btn btn-sm btn-primary" data-page="orders">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>العميل</th>
                                        <th>المنتج</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#ORD-001</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=Ahmed+Mohamed&background=random" alt="" class="rounded-circle me-2" width="32">
                                                <span>أحمد محمد</span>
                                            </div>
                                        </td>
                                        <td>لابتوب Dell XPS</td>
                                        <td>2024-01-15</td>
                                        <td>$1,299</td>
                                        <td><span class="badge bg-success">مكتمل</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#ORD-002</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=Sara+Ali&background=random" alt="" class="rounded-circle me-2" width="32">
                                                <span>سارة علي</span>
                                            </div>
                                        </td>
                                        <td>آيفون 15 برو</td>
                                        <td>2024-01-14</td>
                                        <td>$999</td>
                                        <td><span class="badge bg-warning">قيد المعالجة</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#ORD-003</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=Mohamed+Hassan&background=random" alt="" class="rounded-circle me-2" width="32">
                                                <span>محمد حسن</span>
                                            </div>
                                        </td>
                                        <td>سماعات Sony</td>
                                        <td>2024-01-14</td>
                                        <td>$299</td>
                                        <td><span class="badge bg-danger">ملغي</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
       
       @endsection