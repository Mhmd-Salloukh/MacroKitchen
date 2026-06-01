<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MacroKitchen</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Bootstrap JS + Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=DM+Serif+Text&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playwrite+NO:wght@100..400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body {
      background-color: #fdfaf7;
    }
  </style>

  <script>
    // Compact header on scroll
    document.addEventListener('DOMContentLoaded', function () {
      const header = document.querySelector('.header');
      if (!header) return;

      let ticking = false;
      const THRESHOLD_ENTER = 66;
      const THRESHOLD_EXIT = 54;

      function update() {
        const y = window.scrollY || window.pageYOffset;
        const scrolled = header.classList.contains('header--scrolled');
        if (!scrolled && y > THRESHOLD_ENTER) header.classList.add('header--scrolled');
        else if (scrolled && y < THRESHOLD_EXIT) header.classList.remove('header--scrolled');
      }

      window.addEventListener('scroll', function () {
        if (!ticking) {
          window.requestAnimationFrame(function () {
            update();
            ticking = false;
          });
          ticking = true;
        }
      }, { passive: true });
    });

    
  </script>
</head>
<body>

  <!-- Navbar component -->
  <x-nav/>

  <!-- Page content -->
  {{ $slot }}

  <!-- Footer component -->
  <x-footer/>

</body>
</html>