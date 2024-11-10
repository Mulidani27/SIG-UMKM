{{-- Tabel DataTables --}}
@if (session('error'))
@php
  dump(session('error'));
@endphp
@endif

<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous">
</script>

{{-- JavaScript --}}
<script src="{{ asset('template/assets/static/js/initTheme.js') }}"></script>
<script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
<script src="{{ asset('template/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
<script src="{{ asset('template/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('template/assets/static/js/pages/dashboard.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

{{-- Panggil Leaflet Routing Machine --}}
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

{{-- Tabel --}}
<script src="{{ asset('template/assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('template/assets/static/js/pages/simple-datatables.js') }}"></script>

{{-- Tabel DataTables --}}
<script src="{{ asset('template/assets/static/js/components/datatables.js') }}"></script>
{{-- <script src="{{ asset('template/assets/static/js/components/datatables.min.js') }}"></script> --}}
<script>
  $(document).ready( function () {
    $('#myTable').DataTable();
  } );
</script>

{{-- Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session("success"))
  <script>
    Swal.fire({
    title: "Berhasil!",
    text: "{{ session("success") }}!",
    icon: "success"
  });
  </script>
  @elseif(session("failed"))
  <script>
    Swal.fire({
    title: "Terjadi Kesalahan!",
    text: "{{ session("failed") }}",
    icon: "error"
  });
  </script>
@endif

{{-- Dropdown Kecamatan --}}
<script>
  document.getElementById('menu-toggle').addEventListener('click', function() {
      var menu = document.getElementById('menu');
      var toggleIcon = document.getElementById('menu-toggle');

      // Toggle visibility of the menu
      menu.classList.toggle('hidden');

      // Toggle the icon based on the menu visibility
      if (menu.classList.contains('hidden')) {
          toggleIcon.classList.remove('bi-caret-up-fill');
          toggleIcon.classList.add('bi-caret-down-fill');
      } else {
          toggleIcon.classList.remove('bi-caret-down-fill');
          toggleIcon.classList.add('bi-caret-up-fill');
      }
  });
</script>

{{-- Landing Page --}}
<!-- ==== ANIMATE ON SCROLL JS CDN -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- ==== GSAP CDN ==== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js"></script>
<!-- ==== SCRIPT.JS ==== -->
<script>
  const navId = document.getElementById("nav_menu"),
    ToggleBtnId = document.getElementById("toggle_btn"),
    CloseBtnId = document.getElementById("close_btn");

  // ==== SHOW MENU ==== //
  ToggleBtnId.addEventListener("click", () => {
    navId.classList.add("show");
  });

  // ==== HIDE MENU ==== //
  CloseBtnId.addEventListener("click", () => {
    navId.classList.remove("show");
  });

  // ==== Animate on Scroll Initialize  ==== //
  AOS.init();

  // ==== GSAP Animations ==== //
  // ==== LOGO  ==== //
  gsap.from(".logo", {
    opacity: 0,
    y: -10,
    delay: 1,
    duration: 0.5,
  });
  // ==== NAV-MENU ==== //
  gsap.from(".nav_menu_list .nav_menu_item", {
    opacity: 0,
    y: -10,
    delay: 1.4,
    duration: 0.5,
    stagger: 0.3,
  });
  // ==== TOGGLE BTN ==== //
  gsap.from(".toggle_btn", {
    opacity: 0,
    y: -10,
    delay: 1.4,
    duration: 0.5,
  });
  // ==== MAIN HEADING  ==== //
  gsap.from(".main-heading", {
    opacity: 0,
    y: 20,
    delay: 2.4,
    duration: 1,
  });
  // ==== INFO TEXT ==== //
  gsap.from(".info-text", {
    opacity: 0,
    y: 20,
    delay: 2.8,
    duration: 1,
  });
  // ==== CTA BUTTONS ==== //
  gsap.from(".btn_wrapper", {
    opacity: 0,
    y: 20,
    delay: 2.8,
    duration: 1,
  });
  // ==== TEAM IMAGE ==== //
  gsap.from(".team_img_wrapper img", {
    opacity: 0,
    y: 20,
    delay: 3,
    duration: 1,
  });
</script>

{{-- Slider --}}
<script src="https://cdn.jsdelivr.net/npm/leaflet.zoomslider@0.7.0/L.Control.Zoomslider.min.js"></script>