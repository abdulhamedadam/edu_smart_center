<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProgMaker - لوحة التحكم')</title>
    
    <!-- Bootstrap 5 RTL CSS -->


    @include('admin.layout.styles')
</head>
<body>
 

     @include("admin.layout.sidebar")

    <!-- Main Content Wrapper -->
    <div id="content-wrapper">

        @include("admin.layout.header")

        <!-- Main Content -->
        <main id="main-content">
            @yield("content")
        </main>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="delete-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h5>هل أنت متأكد؟</h5>
                    <p class="text-muted">لن تتمكن من استعادة هذا العنصر!</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">
                            <i class="bi bi-trash"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay"></div>

    <!-- Scripts -->
    <!-- Bootstrap 5 JS -->
    @include("admin.layout.scripts")
    @stack('scripts')

</body>
</html>
