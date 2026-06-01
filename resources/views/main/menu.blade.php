<x-layout>

  <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">

  <!-- Main -->
  <div class="menu-container">
    <main class="container-main">
      @foreach($categories as $category)
        <section>
          <div class="category">
            <h1>{{ $category->name }}</h1>
          </div>

          @if($category->items->isEmpty())
            <p class="text-muted">No items in this category yet.</p>
          @else
            <div class="cards-grid">
              @foreach($category->items as $item)
                <article class="menu-card">
                  <div class="img-wrap">
                    <img src="/assets/images/{{ $item->image }}" alt="{{ $item->name }}">
                  </div>
                  <div class="card-body">
                    <div class="title-row">
                      <h4>{{ $item->name }}</h4>
                      <div class="price">${{ $item->base_price }}</div>
                    </div>
                    <p class="desc">{{ $item->description }}</p>
                    <div class="card-footer">
                      <small>{{ $item->calories }} kcal</small>
                      <small>P: {{ $item->proteins }} g</small>
                      <small>C: {{ $item->carbs }} g</small>
                      <small>F: {{ $item->fats }} g</small>

                      @if(session()->has("cart.$item->id"))
                        <button class="add-btn disabled">Added</button>
                      @else
                        <button type="button" class="add-btn" onclick="addToCart({{ $item->id }}, this)">Add</button>
                      @endif
                    </div>
                  </div>
                </article>
              @endforeach
            </div>
          @endif
        </section>
      @endforeach
    </main>
  </div>

  <!-- Scroll-trigger script: observes cards, headings, and images and adds animate-in with stagger -->
  <script>
    (function () {
      function onReady(fn) {
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
          setTimeout(fn, 40);
        } else {
          document.addEventListener('DOMContentLoaded', fn);
        }
      }

      onReady(function () {
        const observerOptions = {
          root: null,
          rootMargin: '0px',
          threshold: 0.18
        };

        const observer = new IntersectionObserver((entries, obs) => {
          entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;

            if (el.classList && el.classList.contains('menu-card')) {
              const parent = el.parentElement;
              const cards = parent ? Array.from(parent.querySelectorAll('.menu-card')) : [];
              const idx = cards.indexOf(el);
              const delay = Math.min(0.6, 0.08 * Math.max(0, idx));
              el.style.transitionDelay = delay + 's';
              el.classList.add('animate-in');
            } else if (el.matches && el.matches('.category h1')) {
              el.classList.add('animate-in');
            } else if (el.tagName === 'IMG' && el.closest('.img-wrap')) {
              // image zoom-in: slight stagger
              el.style.transitionDelay = '0.08s';
              el.classList.add('animate-in');
            } else {
              el.classList.add('animate-in');
            }

            obs.unobserve(el);
          });
        }, observerOptions);

        document.querySelectorAll('.category h1').forEach(h => {
          h.style.backgroundSize = '200% 200%';
          observer.observe(h);
        });

        document.querySelectorAll('.menu-card').forEach(card => observer.observe(card));

        document.querySelectorAll('.menu-card .img-wrap img').forEach(img => observer.observe(img));
      });
    })();

     // Add to cart function
    function addToCart(itemId, btn) {
      const headers = new Headers();
      headers.append("X-CSRF-TOKEN", "{{ csrf_token() }}");
      headers.append("Content-Type", "application/x-www-form-urlencoded");

      const body = new URLSearchParams();
      body.append("qty", 1);

      fetch("/cart/add/" + itemId, {
        method: "POST",
        headers,
        body,
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.status === "success") {
            btn.classList.add("disabled");
            btn.innerText = "Added";
          } else {
            alert(result.message || "Failed to add item.");
          }
        })
        .catch((error) => console.error(error));
    }
  </script>
</x-layout>