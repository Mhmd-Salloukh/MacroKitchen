<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">


<footer class="site-footer" role="contentinfo">
  <div class="footer-container">
    <!-- Brand -->
    <div class="footer-brand">
      <h2><a href="/" class="brand-link">MacroKitchen</a></h2>
      <p class="tagline">Smart meals, simplified.</p>
    </div>

    <!-- Navigation -->
    <nav class="footer-nav">
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="{{route('menu')}}">Menu</a></li>
        <li><a href="{{route('cart')}}">Cart</a></li>
        <li><a href="{{route('myorders')}}">My Orders</a></li>
        
      </ul>
    </nav>

    <!-- Icons -->
    <div class="footer-icons">
      <a class="social-link tiktok" href="https://www.tiktok.com/@macrokitchen_lb?_r=1&_t=ZS-95rZix1YC76" target="_blank" aria-label="TikTok">
        <img src="/assets/icons/tiktok-logo-thin-svgrepo-com.svg" alt="TikTok">
      </a>
      <a class="social-link facebook" href="https://www.facebook.com" target="_blank" aria-label="Facebook">
        <img src="/assets/icons/facebook-176-svgrepo-com.svg" alt="Facebook">
      </a>
      <a class="social-link instagram" href="https://www.instagram.com/macrokitchen_lb?igsh=MW5ndzlraThleDBmag==" target="_blank" aria-label="Instagram">
        <img src="/assets/icons/instagram-svgrepo-com.svg" alt="Instagram">
      </a>
      <a class="social-link whatsapp" href="https://wa.me/78827037" target="_blank" aria-label="WhatsApp">
        <img src="/assets/icons/whatsapp-svgrepo-com.svg" alt="WhatsApp">
      </a>
    </div>

    <!-- Legal -->
    <div class="footer-legal">
      <p>Contact Us! <a href="mailto:hello@macrokitchen.com">hello@macrokitchen.com</a></p>
      <p>© <span id="currentYear"></span> MacroKitchen. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
  document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>