 <!-- jQuery -->
 {{-- <script src="{{ URL::asset('/build/js/jquery-3.7.1.min.js') }}"></script> --}}
 <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

 <!-- Feather Icon JS -->
 {{-- <script src="{{ URL::asset('/build/js/feather.min.js') }}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

 <!-- Slimscroll JS -->
 {{-- <script src="{{ URL::asset('/build/js/jquery.slimscroll.min.js') }}"></script> --}}
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js" integrity="sha512-cJMgI2OtiquRH4L9u+WQW+mz828vmdp9ljOcm/vKTQ7+ydQUktrPVewlykMgozPP+NUBbHdeifE6iJ6UVjNw5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <!-- Bootstrap Core JS -->
 {{-- <script src="{{ URL::asset('/build/js/bootstrap.bundle.min.js') }}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

 <!-- Chart JS -->
 <script src="{{ URL::asset('/build/plugins/apexchart/apexcharts.min.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/apexchart/chart-data.js') }}"></script>

 <!-- Sweetalert 2 -->
 {{-- <script src="{{ URL::asset('/build/plugins/sweetalert/sweetalert2.all.min.js') }}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="{{ URL::asset('/build/plugins/sweetalert/sweetalerts.min.js') }}"></script>

 <!-- Swiper JS -->
 <script src="{{ URL::asset('/build/plugins/swiper/swiper.min.js') }}"></script>

 <!-- FancyBox JS -->
 <script src="{{ URL::asset('/build/plugins/fancybox/jquery.fancybox.min.js') }}"></script>

 <!-- Select2 JS -->
 <script src="{{ URL::asset('/build/plugins/select2/js/select2.min.js') }}"></script>

 <!-- Datetimepicker JS -->
 <script src="{{ URL::asset('/build/js/moment.min.js') }}"></script>
 <script src="{{ URL::asset('/build/js/bootstrap-datetimepicker.min.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/daterangepicker/daterangepicker.js') }}"></script>

 <!-- Bootstrap Tagsinput JS -->
 <script src="{{ URL::asset('/build/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>

 <!-- Datatable JS -->
 <script src="{{ URL::asset('/build/js/jquery.dataTables.min.js') }}"></script>
 <script src="{{ URL::asset('/build/js/dataTables.bootstrap5.min.js') }}"></script>


 <!-- Mobile Input -->
 <script src="{{ URL::asset('/build/plugins/intltelinput/js/intlTelInput.js') }}"></script>

 <script src="{{ URL::asset('/build/js/plyr-js.js') }}"></script>

 <!-- Owl Carousel -->
 <script src="{{ URL::asset('/build/js/owl.carousel.min.js') }}"></script>

 @if (Route::is(['dashboard','/']))
     <!-- Rater JS -->
     <script src="{{ URL::asset('/build/plugins/rater-js/index.js') }}"></script>
     <!-- Internal Ratings JS -->
     {{-- <script src="{{ URL::asset('/build/js/ratings.js') }}"></script> --}}
 @endif

 <!-- Custom JS -->
 <script src="{{ URL::asset('/build/js/theme-script.js') }}"></script>
 <script src="{{ URL::asset('/build/js/script.js') }}"></script>
