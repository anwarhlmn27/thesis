<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Header start
        ***********************************-->
        @include('layout.navbar')
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        @include('layout.sidebar')
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">

                @yield('content')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        @include('layout.footer')
        <!--**********************************
            Footer end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!-- Global Interactive PDF Viewer Modal -->
    <div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="pdfPreviewModalLabel"><i class="fa fa-file-pdf-o me-2"></i> Preview Dokumen PDF</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="background-color: #525659;">
                    <iframe id="pdfPreviewIframe" src="" width="100%" height="650px" style="border: none;"></iframe>
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <a id="pdfOpenNewTabBtn" href="#" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-external-link me-1"></i> Buka di Tab Baru
                    </a>
                    <div>
                        <a id="pdfDownloadBtn" href="#" download class="btn btn-primary btn-sm me-2">
                            <i class="fa fa-download me-1"></i> Unduh File PDF
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.js')
    @yield('scripts')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: "Terdapat kesalahan pengisian data!"
            });
        @endif

        // Global confirmation function for delete forms
        function confirmDelete(event, form) {
            event.preventDefault();
            const confirmMsg = form.getAttribute('data-confirm-message') || 'Apakah Anda yakin ingin menghapus data ini?';
            Swal.fire({
                title: 'Konfirmasi',
                text: confirmMsg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        // Global helper for Interactive PDF Modal Preview
        function previewPdf(url, title = 'Preview Dokumen PDF') {
            document.getElementById('pdfPreviewModalLabel').innerHTML = '<i class="fa fa-file-pdf-o me-2"></i> ' + title;
            document.getElementById('pdfPreviewIframe').src = url;
            document.getElementById('pdfOpenNewTabBtn').href = url;
            document.getElementById('pdfDownloadBtn').href = url;
            
            var pdfModal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
            pdfModal.show();
        }
    </script>
</body>
</html>