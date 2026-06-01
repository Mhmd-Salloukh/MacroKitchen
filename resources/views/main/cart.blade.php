<x-layout>
  <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">

  <main class="page">
    <aside class="cart" aria-labelledby="cartTitle">
      <h2 id="cartTitle" class="section-title" data-animate-target="title">Your Cart</h2>
      <p class="subtitle" data-animate-target="subtitle">Review items, add extras, and proceed to checkout.</p>

      @forelse ($cart as $item)
        <div class="cart-item card" id="item-{{ $item['id'] }}" data-animate-target="card">
          <div class="ci-meta1">
            <div class="ci-meta">
              <div class="ci-main">
                <h3 class="item-name">{{ $item['name'] }}</h3>
                <div class="item-macros">
                  <small class="muted">{{ $item['calories'] }} kcal</small>
                  <small class="muted">P: {{ $item['proteins'] }}g</small>
                  <small class="muted">C: {{ $item['carbs'] }}g</small>
                  <small class="muted">F: {{ $item['fats'] }}g</small>
                </div>
              </div>
            </div>

            <div class="ci-controls">
              <button class="btn-qty btn-outline" type="button" aria-label="Decrease quantity" onclick="decrementCart({{ $item['id'] }})">−</button>
              <span class="ci-qty" id="quantity-{{ $item['id'] }}">{{ $item['qty'] }}</span>
              <button class="btn-qty btn-cta" type="button" aria-label="Increase quantity" onclick="incrementCart({{ $item['id'] }})">+</button>

              <button type="button" id="remove-{{ $item['id'] }}" onclick="removeFromCart({{ $item['id'] }})" class="delete-btn" aria-label="Remove item">
                <i class="fas fa-trash" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <!-- Extras for this specific item -->
          <div class="extras1 extras-form" id="extras-form-{{ $item['id'] }}" data-animate-target="extras">
            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
            <div class="extras" id="extras-{{ $item['id'] }}">
              @if(!empty($itemExtras[$item['id']] ?? []))
                @foreach ($itemExtras[$item['id']] as $extra)
                  <div class="extra-row">
                    <label for="extra_{{ $item['id'] }}_{{ $extra['id'] }}">
                      <input
                        id="extra_{{ $item['id'] }}_{{ $extra['id'] }}"
                        type="checkbox"
                        name="extras[]"
                        value="{{ $extra['id'] }}"
                        @if(!empty($item['extras']) && in_array($extra['id'], $item['extras'])) checked @endif
                        onchange="UpdateExtras({{ $item['id'] }}, {{ $extra['id'] }}, this)"
                      >
                      <span class="extra-name">{{ $extra['name'] }}</span>
                    </label>

                    
                      <div class="extra-price">+${{ number_format($extra['price'], 2) }}</div>
                      <div class="extra-meta">
                      @if(isset($extra['calories'])) <div class="extra-calories">+{{ $extra['calories'] }} kcal</div> @endif
                      @if(isset($extra['proteins'])) <div class="extra-proteins">+{{ $extra['proteins'] }} P</div> @endif
                      @if(isset($extra['carbs'])) <div class="extra-carbs">+{{ $extra['carbs'] }} C</div> @endif
                      @if(isset($extra['fats'])) <div class="extra-fats">+{{ $extra['fats'] }} F</div> @endif
                    </div>
                  </div>
                @endforeach
              @else
                <p class="text-muted">No extras available at the moment.</p>
              @endif
            </div>
          </div>
         
        </div>
      @empty
        <p class="text-muted empty-message">Your cart is currently empty.</p>
      @endforelse

      <!-- Note stays here, linked to checkout form -->
      <div class="mb-3" id="order-note-container" style="{{ empty($cart) ? 'display:none;' : '' }}" data-animate-target="note">
        <label for="order-note"><strong>Special Note for Kitchen</strong></label>
        <input type="text" id="order-note" name="note" class="form-control"
          placeholder="No onions, no pickles" value="{{ old('note') }}"
          form="checkout-form">
      </div>

      <!-- Summary -->
      <div class="summary" data-animate-target="summary">
        <div class="sub">
          <div class="row1">
            <div class="row">
              <div><strong>Subtotal</strong></div>
              <div id="subtotal-amount" class="subtotal-amount">${{ number_format($subtotal ?? 0, 2) }}</div>
            </div>
            <div class="row">
              <div><strong>Extras</strong></div>
              <div id="extras-amount" class="extras-amount">${{ number_format($extras_total ?? 0, 2) }}</div>
            </div>
            <div class="row total">
              <div><strong>Total</strong></div>
              <div id="total-amount" class="total-amount">${{ number_format($total ?? 0, 2) }}</div>
            </div>
          </div>

          <div class="row2">
            <div class="row">
              <div><strong>Subtotal Macros</strong></div>
              <div class="macros-values">
                <span id="subtotal-calories" class="macro-calories1">{{ $subtotal_calories ?? 0 }} kcal</span>
                <span id="subtotal-proteins" class="macro-proteins1">P: {{ $subtotal_proteins ?? 0 }}g</span>
                <span id="subtotal-carbs" class="macro-carbs1">C: {{ $subtotal_carbs ?? 0 }}g</span>
                <span id="subtotal-fats" class="macro-fats1">F: {{ $subtotal_fats ?? 0 }}g</span>
              </div>
            </div>

            <div class="row">
              <div><strong>Extras Macros</strong></div>
              <div class="macros-values">
                <span id="extras-calories" class="macro-calories2">{{ $extras_calories ?? 0 }} kcal</span>
                <span id="extras-proteins" class="macro-proteins2">P: {{ $extras_proteins ?? 0 }}g</span>
                <span id="extras-carbs" class="macro-carbs2">C: {{ $extras_carbs ?? 0 }}g</span>
                <span id="extras-fats" class="macro-fats2">F: {{ $extras_fats ?? 0 }}g</span>
              </div>
            </div>

            <div class="row total">
              <div><strong>Total Macros</strong></div>
              <div class="macros-values">
                <span id="total-calories" class="macro-calories3">{{ $total_calories ?? 0 }} kcal</span>
                <span id="total-proteins" class="macro-proteins3">P: {{ $total_proteins ?? 0 }}g</span>
                <span id="total-carbs" class="macro-carbs3">C: {{ $total_carbs ?? 0 }}g</span>
                <span id="total-fats" class="macro-fats3">F: {{ $total_fats ?? 0 }}g</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Checkout + Continue Shopping side by side -->
        <div class="checkout">

          <a href="{{ route('menu') }}">
            <button class="btn-secondary btn-outline" type="button">Continue Shopping</button>
          </a>
          
          <form id="checkout-form" action="{{ route('cart.order') }}" method="POST" >
            @csrf
            <button class="btn-checkout btn-cta" type="submit">Checkout</button>
          </form>

          
        </div>
      </div>
    </aside>
  </main>

  <script>
   
  // UpdateCart and cart actions
  function UpdateCart(result) {
    const summaryEl = document.querySelector('.summary');
    const existingMsg = document.querySelector('.empty-message');

    if (result.empty) {
      if (!existingMsg && summaryEl) {
        summaryEl.insertAdjacentHTML(
          'beforebegin',
          '<p class="text-muted empty-message">Your cart is currently empty.</p>'
        );
      }
    } else {
      if (existingMsg) existingMsg.remove();
    }

    document.getElementById('subtotal-amount').textContent =
      '$' + parseFloat(result.newSubtotal).toFixed(2);
    document.getElementById('extras-amount').textContent =
      '$' + parseFloat(result.extras_total).toFixed(2);
    document.getElementById('total-amount').textContent =
      '$' + parseFloat(result.total).toFixed(2);

    document.getElementById('subtotal-calories').textContent =
      result.subtotal_calories + ' kcal';
    document.getElementById('subtotal-proteins').textContent =
      'P: ' + result.subtotal_proteins + 'g';
    document.getElementById('subtotal-carbs').textContent =
      'C: ' + result.subtotal_carbs + 'g';
    document.getElementById('subtotal-fats').textContent =
      'F: ' + result.subtotal_fats + 'g';

    document.getElementById('extras-calories').textContent =
      result.extras_calories + ' kcal';
    document.getElementById('extras-proteins').textContent =
      'P: ' + result.extras_proteins + 'g';
    document.getElementById('extras-carbs').textContent =
      'C: ' + result.extras_carbs + 'g';
    document.getElementById('extras-fats').textContent =
      'F: ' + result.extras_fats + 'g';

    document.getElementById('total-calories').textContent =
      result.total_calories + ' kcal';
    document.getElementById('total-proteins').textContent =
      'P: ' + result.total_proteins + 'g';
    document.getElementById('total-carbs').textContent =
      'C: ' + result.total_carbs + 'g';
    document.getElementById('total-fats').textContent =
      'F: ' + result.total_fats + 'g';
  }

  function incrementCart(itemId) {
    const headers = new Headers();
    headers.append("X-CSRF-TOKEN", "{{ csrf_token() }}");

    fetch("/cart/increment/" + itemId, { method: "POST", headers })
      .then(res => res.json())
      .then(result => {
        if (result.status === 'success') {
          const qtyElement = document.getElementById('quantity-' + itemId);
          if (qtyElement) qtyElement.textContent = parseInt(qtyElement.textContent) + 1;
          UpdateCart(result);
        } else {
          alert(result.message);
        }
      })
      .catch(err => console.error(err));
  }

  function decrementCart(itemId) {
    const headers = new Headers();
    headers.append("X-CSRF-TOKEN", "{{ csrf_token() }}");

    fetch("/cart/decrement/" + itemId, { method: "POST", headers })
      .then(res => res.json())
      .then(result => {
        if (result.status === 'success') {
          const qtyElement = document.getElementById('quantity-' + itemId);
          if (qtyElement) qtyElement.textContent = parseInt(qtyElement.textContent) - 1;
          UpdateCart(result);
        } else {
          alert(result.message);
        }
      })
      .catch(err => console.error(err));
  }

  function removeFromCart(itemId) {
    const headers = new Headers();
    headers.append("X-CSRF-TOKEN", "{{ csrf_token() }}");

    fetch("/cart/remove/" + itemId, { method: "POST", headers })
      .then(res => res.json())
      .then(result => {
        if (result.status === 'success') {
          const itemEl = document.getElementById('item-' + itemId);
          if (itemEl) itemEl.remove();

          // Hide the note immediately if no items remain
          const remaining = document.querySelectorAll('.cart-item').length;
          if (remaining === 0) {
            const noteContainer = document.getElementById('order-note-container');
            if (noteContainer) noteContainer.style.display = 'none';
            result.empty = true;
          }

          UpdateCart(result);
        } else {
          alert(result.message);
        }
      })
      .catch(err => console.error(err));
  }

  function UpdateExtras(itemId, extraId, checkbox) {
    const headers = new Headers();
    headers.append("X-CSRF-TOKEN", "{{ csrf_token() }}");
    headers.append("Content-Type", "application/json");

    fetch("/cart/extras", {
      method: "POST",
      headers,
      body: JSON.stringify({
        item_id: itemId,
        extra_id: extraId,
        checked: checkbox.checked
      }),
    })
      .then(res => res.json())
      .then(result => {
        if (result.status === 'success') {
          UpdateCart(result);
        } else {
          alert(result.message);
        }
      })
      .catch(err => console.error(err));
  }

  // IntersectionObserver for entrance animations
  (function () {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('[data-animate-target]').forEach(el => observer.observe(el));
  })();
  </script>
</x-layout>