/**
 * ProgMaker Admin Dashboard - DataTables Module
 * Advanced DataTables with search, filters, and pagination
 */

$(document).ready(function() {
    'use strict';

    // Sample data for users
    const usersData = [
        { id: 1, name: 'أحمد محمد', email: 'ahmed@example.com', role: 'admin', status: 'active', date: '2024-01-15', avatar: 'Ahmed+Mohamed' },
        { id: 2, name: 'سارة علي', email: 'sara@example.com', role: 'editor', status: 'active', date: '2024-01-14', avatar: 'Sara+Ali' },
        { id: 3, name: 'محمد حسن', email: 'mohamed@example.com', role: 'user', status: 'inactive', date: '2024-01-13', avatar: 'Mohamed+Hassan' },
        { id: 4, name: 'فاطمة أحمد', email: 'fatima@example.com', role: 'user', status: 'active', date: '2024-01-12', avatar: 'Fatima+Ahmed' },
        { id: 5, name: 'عمر خالد', email: 'omar@example.com', role: 'editor', status: 'active', date: '2024-01-11', avatar: 'Omar+Khaled' },
        { id: 6, name: 'نورا سامي', email: 'nora@example.com', role: 'user', status: 'banned', date: '2024-01-10', avatar: 'Nora+Sami' },
        { id: 7, name: 'خالد عبدالله', email: 'khaled@example.com', role: 'user', status: 'active', date: '2024-01-09', avatar: 'Khaled+Abdullah' },
        { id: 8, name: 'ليلى محمود', email: 'laila@example.com', role: 'editor', status: 'active', date: '2024-01-08', avatar: 'Laila+Mahmoud' },
        { id: 9, name: 'يوسف إبراهيم', email: 'youssef@example.com', role: 'user', status: 'inactive', date: '2024-01-07', avatar: 'Youssef+Ibrahim' },
        { id: 10, name: 'هدى كريم', email: 'huda@example.com', role: 'admin', status: 'active', date: '2024-01-06', avatar: 'Huda+Karim' },
        { id: 11, name: 'عبدالرحمن فؤاد', email: 'abdelrahman@example.com', role: 'user', status: 'active', date: '2024-01-05', avatar: 'Abdelrahman+Fuad' },
        { id: 12, name: 'مريم عادل', email: 'mariam@example.com', role: 'editor', status: 'active', date: '2024-01-04', avatar: 'Mariam+Adel' }
    ];

    // Sample data for products
    const productsData = [
        { id: 1, name: 'لابتوب Dell XPS 13', category: 'electronics', price: 1299, stock: 15, status: 'active', image: 'Laptop' },
        { id: 2, name: 'آيفون 15 برو', category: 'electronics', price: 999, stock: 8, status: 'active', image: 'iPhone' },
        { id: 3, name: 'سماعات Sony WH-1000XM5', category: 'electronics', price: 349, stock: 25, status: 'active', image: 'Headphones' },
        { id: 4, name: 'ماوس لوجيتك MX Master', category: 'electronics', price: 99, stock: 50, status: 'active', image: 'Mouse' },
        { id: 5, name: 'كيبورد ميكانيكي', category: 'electronics', price: 149, stock: 3, status: 'low_stock', image: 'Keyboard' },
        { id: 6, name: 'شاشة Samsung 27"', category: 'electronics', price: 399, stock: 0, status: 'out_of_stock', image: 'Monitor' },
        { id: 7, name: 'طابعة HP LaserJet', category: 'electronics', price: 199, stock: 12, status: 'active', image: 'Printer' },
        { id: 8, name: 'راوتر WiFi 6', category: 'electronics', price: 179, stock: 20, status: 'active', image: 'Router' },
        { id: 9, name: 'كاميرا ويب Logitech', category: 'electronics', price: 79, stock: 30, status: 'active', image: 'Webcam' },
        { id: 10, name: 'ميكروفون Blue Yeti', category: 'electronics', price: 129, stock: 5, status: 'low_stock', image: 'Microphone' }
    ];

    // Role badges
    const roleBadges = {
        admin: '<span class="badge bg-danger">مدير</span>',
        editor: '<span class="badge bg-info">محرر</span>',
        user: '<span class="badge bg-secondary">مستخدم</span>'
    };

    // Status badges
    const statusBadges = {
        active: '<span class="badge bg-success">نشط</span>',
        inactive: '<span class="badge bg-warning">غير نشط</span>',
        banned: '<span class="badge bg-danger">محظور</span>',
        low_stock: '<span class="badge bg-warning">مخزون منخفض</span>',
        out_of_stock: '<span class="badge bg-danger">نفذ المخزون</span>'
    };

    // Category names
    const categoryNames = {
        electronics: 'إلكترونيات',
        clothing: 'ملابس',
        food: 'أطعمة',
        books: 'كتب'
    };

    /**
     * Initialize Users DataTable
     */
    function initUsersTable() {
        const table = $('#usersTable');
        if (table.length === 0) return;

        // Prepare data
        const data = usersData.map(user => ({
            id: user.id,
            user: `
                <div class="user-cell">
                    <img src="https://ui-avatars.com/api/?name=${user.avatar}&background=random" alt="${user.name}" class="rounded-circle">
                    <div class="user-info">
                        <span class="user-name">${user.name}</span>
                        <span class="user-email">${user.email}</span>
                    </div>
                </div>
            `,
            email: user.email,
            role: roleBadges[user.role],
            status: statusBadges[user.status],
            date: user.date,
            actions: `
                <div class="action-buttons">
                    <button class="action-btn view" onclick="viewUser(${user.id})" title="عرض">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editUser(${user.id})" title="تعديل">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteUser(${user.id})" title="حذف">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `
        }));

        table.DataTable({
            data: data,
            columns: [
                { data: 'id', width: '60px' },
                { data: 'user', orderable: false },
                { data: 'email', visible: false },
                { data: 'role', width: '100px' },
                { data: 'status', width: '100px' },
                { data: 'date', width: '120px' },
                { data: 'actions', orderable: false, width: '120px', className: 'text-center' }
            ],
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
                search: '',
                searchPlaceholder: 'بحث...'
            },
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'الكل']],
            order: [[0, 'desc']],
            stripeClasses: [],
            initComplete: function() {
                // Add custom filter for role
                const roleFilter = `
                    <div class="filter-item">
                        <label>الدور</label>
                        <select class="form-select form-select-sm" id="roleFilter">
                            <option value="">الكل</option>
                            <option value="admin">مدير</option>
                            <option value="editor">محرر</option>
                            <option value="user">مستخدم</option>
                        </select>
                    </div>
                `;
                
                // Add custom filter for status
                const statusFilter = `
                    <div class="filter-item">
                        <label>الحالة</label>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">الكل</option>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                            <option value="banned">محظور</option>
                        </select>
                    </div>
                `;

                $(roleFilter + statusFilter).insertBefore(table.closest('.dataTables_wrapper').find('.dataTables_filter'));

                // Bind filter events
                $('#roleFilter, #statusFilter').on('change', function() {
                    table.DataTable().draw();
                });
            }
        });

        // Custom filter function
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'usersTable') return true;

            const roleFilter = $('#roleFilter').val();
            const statusFilter = $('#statusFilter').val();
            
            const role = usersData[dataIndex].role;
            const status = usersData[dataIndex].status;

            if (roleFilter && role !== roleFilter) return false;
            if (statusFilter && status !== statusFilter) return false;

            return true;
        });
    }

    /**
     * Initialize Products DataTable
     */
    function initProductsTable() {
        const table = $('#productsTable');
        if (table.length === 0) return;

        // Prepare data
        const data = productsData.map(product => ({
            id: product.id,
            product: `
                <div class="product-cell">
                    <img src="https://ui-avatars.com/api/?name=${product.image}&background=random" alt="${product.name}">
                    <div class="product-info">
                        <span class="product-name">${product.name}</span>
                        <span class="product-category">${categoryNames[product.category]}</span>
                    </div>
                </div>
            `,
            category: categoryNames[product.category],
            price: `$${product.price}`,
            stock: product.stock,
            status: statusBadges[product.status] || statusBadges.active,
            actions: `
                <div class="action-buttons">
                    <button class="action-btn view" onclick="viewProduct(${product.id})" title="عرض">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editProduct(${product.id})" title="تعديل">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteProduct(${product.id})" title="حذف">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `
        }));

        table.DataTable({
            data: data,
            columns: [
                { data: 'id', width: '60px' },
                { data: 'product', orderable: false },
                { data: 'category', width: '100px' },
                { data: 'price', width: '100px' },
                { data: 'stock', width: '80px' },
                { data: 'status', width: '120px' },
                { data: 'actions', orderable: false, width: '120px', className: 'text-center' }
            ],
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
                search: '',
                searchPlaceholder: 'بحث...'
            },
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'الكل']],
            order: [[0, 'desc']],
            stripeClasses: []
        });
    }

    // Initialize tables
    initUsersTable();
    initProductsTable();

    // Expose global functions
    window.viewUser = function(id) {
        const user = usersData.find(u => u.id === id);
        Swal.fire({
            title: user.name,
            html: `
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=${user.avatar}&background=random&size=128" class="rounded-circle mb-3">
                    <p><strong>البريد:</strong> ${user.email}</p>
                    <p><strong>الدور:</strong> ${roleBadges[user.role]}</p>
                    <p><strong>الحالة:</strong> ${statusBadges[user.status]}</p>
                    <p><strong>تاريخ التسجيل:</strong> ${user.date}</p>
                </div>
            `,
            confirmButtonText: 'إغلاق',
            confirmButtonColor: '#6366f1'
        });
    };

    window.editUser = function(id) {
        const user = usersData.find(u => u.id === id);
        Swal.fire({
            title: 'تعديل مستخدم',
            html: `
                <form id="editUserForm" class="text-start">
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" class="form-control" value="${user.name}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" value="${user.email}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <select class="form-select">
                            <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>مدير</option>
                            <option value="editor" ${user.role === 'editor' ? 'selected' : ''}>محرر</option>
                            <option value="user" ${user.role === 'user' ? 'selected' : ''}>مستخدم</option>
                        </select>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'حفظ',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#6366f1'
        });
    };

    window.deleteUser = function(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تتمكن من استعادة هذا المستخدم!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'تم الحذف!',
                    text: 'تم حذف المستخدم بنجاح.',
                    icon: 'success',
                    confirmButtonColor: '#6366f1'
                });
            }
        });
    };

    window.viewProduct = function(id) {
        const product = productsData.find(p => p.id === id);
        Swal.fire({
            title: product.name,
            html: `
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=${product.image}&background=random&size=128" class="rounded mb-3">
                    <p><strong>الفئة:</strong> ${categoryNames[product.category]}</p>
                    <p><strong>السعر:</strong> $${product.price}</p>
                    <p><strong>المخزون:</strong> ${product.stock}</p>
                    <p><strong>الحالة:</strong> ${statusBadges[product.status] || statusBadges.active}</p>
                </div>
            `,
            confirmButtonText: 'إغلاق',
            confirmButtonColor: '#6366f1'
        });
    };

    window.editProduct = function(id) {
        const product = productsData.find(p => p.id === id);
        Swal.fire({
            title: 'تعديل منتج',
            html: `
                <form id="editProductForm" class="text-start">
                    <div class="mb-3">
                        <label class="form-label">اسم المنتج</label>
                        <input type="text" class="form-control" value="${product.name}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">السعر</label>
                        <input type="number" class="form-control" value="${product.price}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المخزون</label>
                        <input type="number" class="form-control" value="${product.stock}">
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'حفظ',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#6366f1'
        });
    };

    window.deleteProduct = function(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تتمكن من استعادة هذا المنتج!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'تم الحذف!',
                    text: 'تم حذف المنتج بنجاح.',
                    icon: 'success',
                    confirmButtonColor: '#6366f1'
                });
            }
        });
    };
});
