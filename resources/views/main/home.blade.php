<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">

<x-layout>
  <!-- Hero Section -->
  <div class="home-page">
    <div class="hero-section">
      <div class="hero-text">
        <h1 class="hero-title">FOR THOSE WHO NEVER</h1>
        <div class="hero-subtitle">
          <span>HALF EAT</span>
          <span>HALF REP</span>
          <span>HALF LIVE</span>
        </div>
      </div>

      <div class="hero-image">
        <img src="/assets/images/test2.jpeg" alt="Hero Image" class="section-image">
      </div>
    </div>

    <!-- Home Body 2 Slogan Block -->
    <div class="home-body-2">
      <div class="slogan-block">
        <h2 class="slogan-main">Meals that match your mindset.</h2>
      </div>
    </div>

    <!-- Carousel -->
    <div id="carouselExampleAutoplaying" class="carousel slide mt-4" data-bs-ride="carousel" data-bs-interval="4000">
      <!-- Indicators -->
      <div class="carousel-indicators">
        @foreach ($items->chunk(3) as $chunkIndex => $chunk)
          <button type="button" data-bs-target="#carouselExampleAutoplaying"
                  data-bs-slide-to="{{ $chunkIndex }}"
                  class="{{ $chunkIndex === 0 ? 'active' : '' }}"
                  aria-current="{{ $chunkIndex === 0 ? 'true' : 'false' }}"
                  aria-label="Slide {{ $chunkIndex + 1 }}"></button>
        @endforeach
      </div>

      <div class="carousel-inner">
        @foreach ($items->chunk(3) as $chunkIndex => $chunk)
          <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
            <div class="carousel-card-row">
              @foreach ($chunk as $item)
                <div class="home-card">
                  <img src="/assets/images/{{ $item->image }}" class="card-img-top" alt="Menu item image">
                  <div class="home-card-body">
                    <div class="home-title-row">
                      <h5 class="card-title">{{ $item->name }}</h5>
                      <div class="home-price">${{ $item->base_price }}</div>
                    </div>
                    <div class="card-text1">
                      <p class="card-text">{{ $item->description }}</p>
                    </div>
                    <div class="home-card-footer">
                      <small>{{ $item->calories }}Kcal </small>
                      <small>P: {{ $item->proteins }}g</small>
                      <small>C: {{ $item->carbs }}g</small>
                      <small>F: {{ $item->fats }}g</small>

                      <input type="hidden" name="qty" value="1" id="quantity-{{ $item->id }}">
                      @if (@session('cart')[$item->id])
                        <button class="home-add-btn disabled">Added</button>
                      @else
                        <button type="button" class="home-add-btn" onclick="addToCart({{ $item->id }}, this)">Add</button>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <!-- Home Body 3: Macro Calculator CTA -->
    <div class="home-body-3" id="home-body-3">
      <div class="slogan-image2">

        <!-- Image Block (left) -->
        <div class="image-2">
          <img src="/assets/images/meal2.jpeg" alt="MacroKitchen Meals" class="section-image" data-animate-target="image">
        </div>

        <!-- Text Block (right) -->
        <div class="slogan-4">
          <h1 data-animate-target="headline">
            Join the MacroKitchen Lifestyle
            <span class="icon-dumbbell">
              <img src="/assets/images/dumbbell3.png" alt="Dumbbell Icon" class="icon-dumbbell-img" data-animate-target="icon">
            </span>
          </h1>
          <div class="slogan-5" data-animate-target="copy">
            <p>Discover the nutrition that fuels it.</p>
            <button type="button" class="btn btn-outline-success plan-button macro-btn" data-animate-target="cta">
              Macro Calculator
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Intersection Observer script with cta mapped to fade-only -->
  <script>
    (function () {
      const animationMap = {
        headline: 'slogan-animate',
        copy: 'slogan-animate-delay',
        cta: 'slogan-fade-only',
        image: 'slogan-animate',
        icon: 'slogan-animate-delay'
      };

      function addAnimClass(el, cls) {
        if (!el) return;
        if (!el.classList.contains(cls)) el.classList.add(cls);
      }

      const container = document.getElementById('home-body-3');
      if (!container) return;

      const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.8
      };

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting && entry.intersectionRatio >= 0.8) {
            const targets = container.querySelectorAll('[data-animate-target]');
            targets.forEach(el => {
              const key = el.getAttribute('data-animate-target');
              const cls = animationMap[key];
              if (cls) addAnimClass(el, cls);
            });
            obs.unobserve(entry.target);
          }
        });
      }, options);

      observer.observe(container);

      function immediateCheck() {
        const rect = container.getBoundingClientRect();
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        const visibleTop = Math.max(rect.top, 0);
        const visibleBottom = Math.min(rect.bottom, viewportHeight);
        const visibleHeight = Math.max(0, visibleBottom - visibleTop);
        const elementHeight = rect.height || container.offsetHeight;

        if (elementHeight > 0 && (visibleHeight / elementHeight) >= 0.8) {
          const targets = container.querySelectorAll('[data-animate-target]');
          targets.forEach(el => {
            const key = el.getAttribute('data-animate-target');
            const cls = animationMap[key];
            if (cls) addAnimClass(el, cls);
          });
          observer.unobserve(container);
        }
      }

      window.addEventListener('load', immediateCheck);
      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(immediateCheck, 120);
      });
      setTimeout(immediateCheck, 220);
    })();
  </script>

  <!-- Detach fade-only class from CTA after animation ends -->
  <script>
    (function () {
      function detachButtonAnimation() {
        const ctas = document.querySelectorAll('[data-animate-target="cta"], .slogan-fade-only');
        ctas.forEach(btn => {
          const computedAnimationName = getComputedStyle(btn).animationName;
          if (computedAnimationName === 'none') {
            btn.classList.remove('slogan-fade-only');
            return;
          }

          const onAnimEnd = (ev) => {
            if (ev.animationName && ev.animationName !== 'fadeOpacity') return;
            btn.classList.remove('slogan-fade-only');
            btn.removeEventListener('animationend', onAnimEnd);
          };
          btn.addEventListener('animationend', onAnimEnd);

          setTimeout(() => {
            if (btn.classList.contains('slogan-fade-only')) {
              btn.classList.remove('slogan-fade-only');
            }
          }, 1600);
        });
      }

      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(detachButtonAnimation, 260);
      } else {
        document.addEventListener('DOMContentLoaded', () => setTimeout(detachButtonAnimation, 260));
      }
      window.addEventListener('load', () => setTimeout(detachButtonAnimation, 260));
    })();
  </script>

  
  
</x-layout>