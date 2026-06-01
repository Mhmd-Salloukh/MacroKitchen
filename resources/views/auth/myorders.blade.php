<x-layout>
  <link rel="stylesheet" href="{{ asset('assets/css/myorders.css') }}">

  <main class="orders-page" id="orders-page">
    <section class="page-hero" aria-hidden="false">
      <div class="hero-inner">
        <h1 class="page-heading">My Orders</h1>
      </div>
    </section>

    @if(empty($ordersData) || count($ordersData) === 0)
      <div class="orders-empty-card" role="status" aria-live="polite">
        <div class="empty-emoji">🍱</div>
        <p class="empty-title">No orders yet</p>

        <a href="{{ route('menu') }}" class="empty-cta" role="button" aria-label="Browse the menu and place an order">
          <span class="cta-left">
            <svg class="cta-icon" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path fill="currentColor" d="M12 2a2 2 0 0 1 2 2v6h4a1 1 0 0 1 .8 1.6l-6 8A1 1 0 0 1 12 20v-6H8a1 1 0 0 1-1-1V4a2 2 0 0 1 2-2h3z"/>
            </svg>
            <span class="cta-text">Browse Menu</span>
          </span>
          <span class="cta-badge">Explore</span>
        </a>
      </div>
    @else
      <div class="orders-accordion" role="list" aria-label="Orders list">
        @foreach($ordersData as $order)
          <article class="order-section" role="listitem" aria-labelledby="order-label-{{ $order['id'] }}">
            <header class="order-header">
              <button
                id="order-label-{{ $order['id'] }}"
                class="order-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="details-{{ $order['id'] }}"
                onclick="toggleOrder(this)">
                <div class="order-left">
                  <span class="order-id">🍱 Order #{{ $order['id'] }}</span>
                  <span class="order-date-inline">• {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}</span>
                </div>

                <div class="order-right">
                  <span class="order-price">${{ number_format($order['total_price'] ?? 0, 2) }}</span>
                  <span class="toggle-caret" aria-hidden="true">▾</span>
                </div>
              </button>
            </header>

            <div id="details-{{ $order['id'] }}" class="order-details" hidden>
              <div class="order-meta">
                <div class="meta-left">
                  <strong>Items</strong>
                </div>

      
                <div class="meta-right">
                  <span class="order-status status-{{ $order['status'] }}">{{ ucfirst($order['status']) }}</span>
                </div>
              </div>

              @if(empty($order['items']) || count($order['items']) === 0)
                <div class="order-no-items">No items recorded for this order.</div>
              @else
                <ul class="order-items-list" aria-label="Order items">
                  @foreach($order['items'] as $item)
                    <li class="order-item">
                      <div class="item-left">
                        <span class="item-qty">{{ $item['qty'] }}×</span>
                        <div class="item-main">
                          <div class="item-name">{{ $item['name'] }}</div>

                          @if(!empty($item['extras']) && is_array($item['extras']) && count($item['extras']) > 0)
                            <ul class="item-extras" aria-label="Extras for {{ $item['name'] }}">
                              @foreach($item['extras'] as $extra)
                                <li class="item-extra">{{ $extra }}</li>
                              @endforeach
                            </ul>
                          @endif
                        </div>
                      </div>

                      <div class="item-right">
                        @if(isset($order['status']) && $order['status'] === 'delivered')
                          <button
                            type="button"
                            class="reorder-btn"
                            data-reorder-url="{{ route('cart.reorder', $order['id']) }}"
                            onclick="openReorderModal(this)">
                            Reorder
                          </button>
                        @endif
                      </div>
                    </li>
                  @endforeach
                </ul>
              @endif
              <div class="order-actions">
              </div>
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </main>

  <div id="reorderModal" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="reorderModalTitle">
    <div class="modal-content" role="document">
      <button class="modal-close" onclick="closeModal()" aria-label="Close">✕</button>
      <h2 id="reorderModalTitle" class="modal-title">Confirm Reorder</h2>
      <p class="modal-body">Reordering will replace your current cart with items from this past order. Continue?</p>
      <div class="modal-actions">
        <button class="cancel-btn" onclick="closeModal()">Cancel</button>
        <button id="confirmReorderLink" class="reorder-cta">Reorder</button>
      </div>
    </div>
  </div>

  <script>
    function toggleOrder(btn) {
      const details = document.getElementById(btn.getAttribute('aria-controls'));
      const isOpen = btn.classList.toggle('active');
      btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      details.classList.toggle('open', isOpen);
      if (isOpen) {
        details.hidden = false;
        details.style.maxHeight = details.scrollHeight + 'px';
      } else {
        details.style.maxHeight = null;
        setTimeout(() => { details.hidden = true; }, 360);
      }
    }

    function openReorderModal(el) {
      const url = el.getAttribute('data-reorder-url');
      const link = document.getElementById('confirmReorderLink');
      link.onclick = () => { window.location.href = url; };
      const modal = document.getElementById('reorderModal');
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
      const modal = document.getElementById('reorderModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
      const link = document.getElementById('confirmReorderLink');
      link.onclick = null;
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        const modal = document.getElementById('reorderModal');
        if (modal.classList.contains('show')) closeModal();
      }
    });

    document.getElementById('reorderModal').addEventListener('click', function (e) {
      if (e.target === this) closeModal();
    });
  </script>
</x-layout>