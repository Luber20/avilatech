const products = [
  {
    id: 'audifonos-wave',
    name: 'Audífonos Wave Pro',
    category: 'Audio',
    description: 'Sonido envolvente, diseño cómodo y conexión inalámbrica estable.',
    priceCents: 1200,
    badge: 'Más vendido',
    rating: '4.9',
    image: 'assets/images/audifonos.jpg'
  },
  {
    id: 'mouse-nova',
    name: 'Mouse Nova Wireless',
    category: 'Productividad',
    description: 'Precisión fluida y diseño ergonómico para estudiar o trabajar.',
    priceCents: 800,
    badge: 'Popular',
    rating: '4.8',
    image: 'assets/images/mouse.jpg'
  },
  {
    id: 'teclado-orbit',
    name: 'Teclado Orbit Mini',
    category: 'Escritorio',
    description: 'Formato compacto con teclas suaves y estilo minimalista.',
    priceCents: 1500,
    badge: 'Nuevo',
    rating: '4.7',
    image: 'assets/images/teclado.jpg'
  },
  {
    id: 'smartwatch-pulse',
    name: 'Smartwatch Pulse Fit',
    category: 'Wearables',
    description: 'Un complemento versátil para mantener tus actividades organizadas.',
    priceCents: 1800,
    badge: 'Recomendado',
    rating: '4.9',
    image: 'assets/images/smartwatch.jpg'
  },
  {
    id: 'parlante-beat',
    name: 'Parlante Beat Mini',
    category: 'Audio',
    description: 'Parlante portátil con sonido potente, batería duradera y diseño moderno.',
    priceCents: 1400,
    badge: 'Oferta',
    rating: '4.8',
    image: 'assets/images/parlante.jpg'
  },
  {
    id: 'cargador-volt',
    name: 'Cargador Volt 20W',
    category: 'Energía',
    description: 'Cargador rápido y compacto compatible con celulares, tablets y accesorios.',
    priceCents: 1000,
    badge: 'Esencial',
    rating: '4.7',
    image: 'assets/images/cargador.jpg'
  },
  {
    id: 'memoria-usb-flash',
    name: 'Memoria USB Flash 64GB',
    category: 'Almacenamiento',
    description: 'Unidad portátil de alta velocidad para guardar documentos, fotos y proyectos.',
    priceCents: 700,
    badge: 'Práctico',
    rating: '4.8',
    image: 'assets/images/usb.jpg'
  },
  {
    id: 'soporte-flex-laptop',
    name: 'Soporte Flex para Laptop',
    category: 'Escritorio',
    description: 'Base ergonómica y resistente para mejorar la postura al estudiar o trabajar.',
    priceCents: 1100,
    badge: 'Ergonómico',
    rating: '4.9',
    image: 'assets/images/soporte.jpg'
  }
];

let cart = [];
let payphoneConfig = null;

const productGrid = document.querySelector('#product-grid');
const cartDrawer = document.querySelector('#cart-drawer');
const cartContent = document.querySelector('#cart-content');
const cartCount = document.querySelector('#cart-count');
const checkoutModal = document.querySelector('#checkout-modal');
const checkoutSummary = document.querySelector('#checkout-summary');
const paymentMessage = document.querySelector('#payment-message');
const toast = document.querySelector('#toast');

const currency = new Intl.NumberFormat('es-EC', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 2
});

function formatMoney(cents) {
  return currency.format(cents / 100);
}

function renderProducts() {
  productGrid.innerHTML = products.map(product => `
    <article class="product-card">
      <div class="product-image-wrap">
        <img src="${product.image}" alt="${product.name}" loading="lazy">
        <span class="product-badge">${product.badge}</span>
      </div>
      <div class="product-info">
        <div class="product-meta">
          <span>${product.category}</span>
          <span>★ ${product.rating}</span>
        </div>
        <h3>${product.name}</h3>
        <p>${product.description}</p>
        <div class="product-footer">
          <strong>${formatMoney(product.priceCents)}</strong>
          <button type="button" data-add-product="${product.id}">Agregar <span>+</span></button>
        </div>
      </div>
    </article>
  `).join('');
}

function showToast(message) {
  toast.textContent = message;
  toast.classList.add('visible');
  window.clearTimeout(showToast.timeout);
  showToast.timeout = window.setTimeout(() => toast.classList.remove('visible'), 2600);
}
/* Agregar productos al carrito. */

function addToCart(productId) {
  const product = products.find(item => item.id === productId);
  if (!product) return;

  const existing = cart.find(item => item.id === productId);
  if (existing) {
    showToast('Este producto ya está en tu carrito.');
    openCart();
    return;
  }

  cart.push(product);
  updateCart();
  showToast(`${product.name} agregado al carrito.`);
}

function removeFromCart(productId) {
  cart = cart.filter(item => item.id !== productId);
  updateCart();
}

function getCartTotal() {
  return cart.reduce((total, product) => total + product.priceCents, 0);
}

function updateCart() {
  cartCount.textContent = cart.length;

  if (cart.length === 0) {
    cartContent.innerHTML = `
      <div class="empty-cart">
        <div>🛒</div>
        <h3>Tu carrito está vacío</h3>
        <p>Agrega un accesorio para iniciar una compra de prueba.</p>
        <button type="button" data-close-cart>Explorar productos</button>
      </div>
    `;
    return;
  }

  cartContent.innerHTML = `
    <div class="cart-items">
      ${cart.map(product => `
        <article class="cart-item">
          <img src="${product.image}" alt="${product.name}">
          <div>
            <span>${product.category}</span>
            <h3>${product.name}</h3>
            <strong>${formatMoney(product.priceCents)}</strong>
          </div>
          <button type="button" data-remove-product="${product.id}" aria-label="Eliminar ${product.name}">×</button>
        </article>
      `).join('')}
    </div>
    <div class="cart-total">
      <div><span>Subtotal</span><strong>${formatMoney(getCartTotal())}</strong></div>
      <div><span>Envío</span><strong>Gratis</strong></div>
      <div class="grand-total"><span>Total</span><strong>${formatMoney(getCartTotal())}</strong></div>
      <button class="checkout-button" id="start-checkout" type="button">Continuar al pago <span>→</span></button>
      <small>Transacción simulada en ambiente de pruebas.</small>
    </div>
  `;
}

function openCart() {
  cartDrawer.classList.add('open');
  cartDrawer.setAttribute('aria-hidden', 'false');
  document.body.classList.add('no-scroll');
}

function closeCart() {
  cartDrawer.classList.remove('open');
  cartDrawer.setAttribute('aria-hidden', 'true');
  if (!checkoutModal.classList.contains('open')) document.body.classList.remove('no-scroll');
}

function closeCheckout() {
  checkoutModal.classList.remove('open');
  checkoutModal.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('no-scroll');
  document.querySelector('#pp-button').innerHTML = '';
}



/* REQUEST: crea un identificador único para cada compra. */

function createClientTransactionId() {
  return `Avila-${Date.now()}`;
}


/* Obtiene el Token y StoreID necesarios para comunicarse con Payphone. 
CLIENTE-SERVIDOR */

async function loadPayphoneConfig() { 
  if (payphoneConfig) return payphoneConfig;

  const response = await fetch('api/payphone-config.php', { cache: 'no-store' });
  const data = await response.json();
  if (!response.ok || !data.ok) throw new Error(data.message || 'No se pudo cargar la configuración de Payphone.');

  payphoneConfig = data;
  return payphoneConfig;
}



function renderCheckoutSummary(transactionId) {
  checkoutSummary.innerHTML = `
    <p class="summary-caption">Resumen del pedido</p>
    <div class="summary-products">
      ${cart.map(product => `
        <div class="summary-product">
          <img src="${product.image}" alt="${product.name}">
          <div><strong>${product.name}</strong><small>${product.category}</small></div>
          <span>${formatMoney(product.priceCents)}</span>
        </div>
      `).join('')}
    </div>
    <div class="summary-lines">
      <div><span>Subtotal</span><strong>${formatMoney(getCartTotal())}</strong></div>
      <div><span>Envío</span><strong>Gratis</strong></div>
      <div class="summary-total"><span>Total a pagar</span><strong>${formatMoney(getCartTotal())}</strong></div>
    </div>
    <div class="transaction-reference">
      <span>Referencia del pedido</span>
      <code>${transactionId}</code>
    </div>
  `;
}


/* request principal envia a Payphone datos y crea Cajita de Pagos */

async function startCheckout() {
  if (cart.length === 0) return;

  closeCart();
  checkoutModal.classList.add('open');
  checkoutModal.setAttribute('aria-hidden', 'false');
  document.body.classList.add('no-scroll');

  const transactionId = createClientTransactionId();
  renderCheckoutSummary(transactionId);
  const buttonContainer = document.querySelector('#pp-button');
  buttonContainer.innerHTML = '';
  paymentMessage.className = 'notice';
  paymentMessage.textContent = 'Cargando la Cajita de Pagos segura…';

  try {
    const config = await loadPayphoneConfig();

    if (typeof window.PPaymentButtonBox !== 'function' && typeof PPaymentButtonBox !== 'function') {
      throw new Error('El SDK de Payphone todavía no está disponible. Recarga la página e inténtalo nuevamente.');
    }

    const PayphoneBox = window.PPaymentButtonBox || PPaymentButtonBox;
    const amount = getCartTotal();
    const reference = `Compra AvilaTech: ${cart.map(product => product.name).join(', ')}`.slice(0, 100);

    /* se prepara el request con los datos */

    new PayphoneBox({
      token: config.token,
      clientTransactionId: transactionId,
      amount,
      amountWithoutTax: amount,
      currency: 'USD',
      storeId: config.storeId,
      reference,
      lang: 'es',
      defaultMethod: 'card',
      timeZone: -5,
      optionalParameter: 'Proyecto académico AvilaTech'
    }).render('pp-button');

    paymentMessage.className = 'notice success';
    paymentMessage.textContent = 'Formulario cargado. Completa los datos de prueba para continuar.';
  } catch (error) {
    paymentMessage.className = 'notice error';
    paymentMessage.textContent = error.message;
  }
}

document.addEventListener('click', event => {
  const addButton = event.target.closest('[data-add-product]');
  const removeButton = event.target.closest('[data-remove-product]');
  const closeCartButton = event.target.closest('[data-close-cart]');
  const closeCheckoutButton = event.target.closest('[data-close-checkout]');

  if (addButton) addToCart(addButton.dataset.addProduct);
  if (removeButton) removeFromCart(removeButton.dataset.removeProduct);
  if (closeCartButton) closeCart();
  if (closeCheckoutButton) closeCheckout();
  if (event.target.closest('#open-cart')) openCart();
  if (event.target.closest('#start-checkout')) startCheckout();
});

document.addEventListener('keydown', event => {
  if (event.key === 'Escape') {
    closeCart();
    closeCheckout();
  }
});

renderProducts();
updateCart();