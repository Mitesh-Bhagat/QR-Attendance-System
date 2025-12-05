<div class="container-fluid pt-4 px-4">
     <div class="rounded-top p-4" style="background: rgba(10, 10, 10, 0.8); border-top: 1px solid var(--primary); backdrop-filter: blur(10px);">
         <div class="row">
             <div class="col-12 col-sm-6 text-center text-sm-start" style="color: #aaa;">
                 &copy; <a href="#" style="color: #fff; text-decoration: none;">QR Code Attendance System</a>, All Right Reserved.
             </div>
             <div class="col-12 col-sm-6 text-center text-sm-end" style="color: #aaa;">
                 Designed By <a href="#" style="color: #fff; text-decoration: none;">Mitesh Bhagat</a>
             </div>
         </div>
     </div>
 </div>
 </div>
 <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" id="backToTopBtn" style="display: none;">
    <i class="bi bi-arrow-up"></i>
 </a>
 </div>
 <link href="css/theme of project.css" rel="stylesheet">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
 <script src="lib/chart/chart.min.js"></script>
 <script src="lib/easing/easing.min.js"></script>
 <script src="lib/waypoints/waypoints.min.js"></script>
 <script src="lib/owlcarousel/owl.carousel.min.js"></script>
 <script src="lib/tempusdominus/js/moment.min.js"></script>
 <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
 <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

 <script src="js/main.js"></script>

 <script>
     $(document).ready(function() {
         // Scroll Event
         $(window).scroll(function() {
             if ($(this).scrollTop() > 100) {
                 $('#backToTopBtn').fadeIn('slow');
             } else {
                 $('#backToTopBtn').fadeOut('slow');
             }
         });

         // Click Event
         $('#backToTopBtn').click(function() {
             $('html, body').animate({scrollTop: 0}, 100, 'swing');
             return false;
         });
     });
 </script>

 <script>
     // Check if table exists before initializing to prevent errors
     if ($('#table').length > 0) {
         new DataTable('#table', {
             layout: {
                 topStart: {
                     buttons: [{
                             extend: 'print',
                             exportOptions: {
                                 columns: ':visible'
                             }
                         },
                         'colvis'
                     ]
                 }
             },
             columnDefs: [{
                 targets: 0,
                 visible: false
             }]
         });
     }
 </script>
 </body>

 </html>