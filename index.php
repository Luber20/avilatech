<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="origin-when-cross-origin">
    <title>AvilaTech | Accesorios tecnológicos</title>
    <meta name="description" content="Tienda demostrativa de accesorios tecnológicos con integración de Cajita de Pagos Payphone.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="assets/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.payphonetodoesposible.com/box/v2.0/payphone-payment-box.css">
    <link rel="stylesheet" href="assets/style.css">
    <script type="module" src="https://cdn.payphonetodoesposible.com/box/v2.0/payphone-payment-box.js"></script>
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <a class="brand" href="#inicio" aria-label="AvilaTech inicio">
                <span class="brand-mark">A</span>
                <span>Avila<span>Tech</span></span>
            </a>
            <nav class="main-nav" aria-label="Navegación principal">
                <a href="#inicio">Inicio</a>
                <a href="#productos">Productos</a>
                <a href="#beneficios">Beneficios</a>
            </nav>
            <button class="cart-button" id="open-cart" type="button" aria-label="Abrir carrito">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H7.2M10 20a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/></svg>
                <span>Carrito</span>
                <strong id="cart-count">0</strong>
            </button>
        </div>
    </header>

    <main>
        <section class="hero" id="inicio">
            <div class="container hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow"><span></span> Tecnología al alcance </p>
                    <h1>Accesorios que mejoran tu <em>experiencia digital.</em></h1>
                    <p class="hero-text">Explora una selección de accesorios tecnológicos con una compra rápida y segura mediante Payphone.</p>
                    <div class="hero-actions">
                        <a href="#productos" class="primary-link">Ver productos <span>→</span></a>
                        <a href="#beneficios" class="secondary-link">¿Por qué elegirnos?</a>
                    </div>
                    <div class="trust-row">
                        <div><strong>4.9/5</strong><span>Valoración</span></div>
                        <div><strong>100%</strong><span>Compra segura</span></div>
                        <div><strong>24/7</strong><span>Catálogo online</span></div>
                    </div>
                </div>
                <div class="hero-showcase">
                    <div class="hero-orb"></div>
                    <div class="floating-card delivery-card">
                        <span class="mini-icon">✓</span>
                        <div><strong>Pago protegido</strong><small>Integración Payphone</small></div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=90" alt="Audífonos premium" class="hero-image">
                    <div class="hero-price"><small>Desde</small><strong>$8.00</strong></div>
                </div>
            </div>
        </section>

        <section class="product-section" id="productos">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow"><span></span> Nuestro catálogo</p>
                        <h2>Encuentra tu accesorio ideal</h2>
                    </div>
                    <p>Productos demostrativos para realizar transacciones de prueba en la pasarela de pago.</p>
                </div>
                <div class="product-grid" id="product-grid"></div>
            </div>
        </section>

        <section class="benefits" id="beneficios">
            <div class="container benefits-grid">
                <article>
                    <span class="benefit-icon">01</span>
                    <h3>Compra sencilla</h3>
                    <p>Selecciona tu producto, revisa el resumen y completa el pago desde una interfaz clara.</p>
                </article>
                <article>
                    <span class="benefit-icon">02</span>
                    <h3>Pago integrado</h3>
                    <p>La Cajita de Pagos Payphone se renderiza dentro de la tienda sin complicar el proceso.</p>
                </article>
                <article>
                    <span class="benefit-icon">03</span>
                    <h3>Confirmación inmediata</h3>
                    <p>Al finalizar, la respuesta del API muestra el estado y los datos relevantes de la transacción.</p>
                </article>
            </div>
        </section>
    </main>

    <aside class="drawer" id="cart-drawer" aria-hidden="true">
        <div class="drawer-backdrop" data-close-cart></div>
        <section class="drawer-panel" aria-label="Carrito de compras">
            <header class="drawer-header">
                <div>
                    <p>Tu selección</p>
                    <h2>Carrito de compras</h2>
                </div>
                <button class="icon-button" type="button" data-close-cart aria-label="Cerrar carrito">×</button>
            </header>
            <div class="drawer-body" id="cart-content"></div>
        </section>
    </aside>

    <div class="modal" id="checkout-modal" aria-hidden="true">
        <div class="modal-backdrop" data-close-checkout></div>
        <section class="checkout-card" aria-label="Finalizar compra">
            <header class="checkout-header">
                <div>
                    <p class="checkout-step">Paso final</p>
                    <h2>Completa tu pago</h2>
                </div>
                <button class="icon-button" type="button" data-close-checkout aria-label="Cerrar pago">×</button>
            </header>
            <div class="checkout-layout">
                <div class="checkout-summary" id="checkout-summary"></div>
                <div class="payment-area">
                    <div class="payment-label">
                        <span></span>
                        <div><strong>Pago seguro con Payphone</strong><small>Completa el formulario para continuar</small></div>
                    </div>
                    <div class="notice" id="payment-message">La caja de pago se cargará aquí.</div>
                    <div id="pp-button"></div>
                </div>
            </div>
        </section>
    </div>

    <div class="toast" id="toast" role="status" aria-live="polite"></div>

    <footer class="footer">
        <div class="container footer-content">
            <a class="brand" href="#inicio"><span class="brand-mark">A</span><span>Avila<span>Tech</span></span></a>
            <p>Proyecto académico demostrativo · Integración Payphone en entorno de pruebas</p>
        </div>
    </footer>

    <script src="assets/app.js"></script>
</body>
</html>
