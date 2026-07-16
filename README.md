# AvilaTech + Cajita de Pagos Payphone

Proyecto académico de mini e-commerce para demostrar comunicación cliente-servidor y servidor-servidor con Payphone en ambiente de pruebas.

## Requisitos

- PHP 8 o superior.
- Extensión PHP cURL habilitada.
- Cuenta de Payphone Business y usuario desarrollador.
- Aplicación WEB creada en Payphone Developer.
- TOKEN y STORE ID de la pestaña Credenciales.

## Configuración en Payphone Developer

Configura tu aplicación WEB con:

- Dominio web: `http://localhost:8080/`
- URL de respuesta: `http://localhost:8080/respuesta.php`
- Ambiente: `PRUEBAS`

> Usa `http`, no `https`, para el servidor local de esta práctica.

## Configurar credenciales

Abre `config.php` y reemplaza:

```php
'token' => 'PEGA_AQUI_TU_TOKEN',
'storeId' => 'PEGA_AQUI_TU_STORE_ID',
```

No compartas públicamente tu token.

## Ejecutar el proyecto

Abre una terminal dentro de esta carpeta y ejecuta:

```bash
php -S localhost:8080
```

Después, abre en el navegador:

```text
http://localhost:8080/
```

## Flujo de demostración

1. Agrega uno o varios productos al carrito.
2. Abre el carrito.
3. Presiona **Continuar al pago**.
4. Completa la Cajita de Pagos con datos de prueba.
5. Payphone redirige a `respuesta.php`.
6. `respuesta.php` captura `id` y `clientTransactionId`.
7. El servidor PHP realiza el `POST` hacia el endpoint `/api/confirm`.
8. La página muestra el estado, el ID de transacción y el JSON completo del response.

## Archivos importantes

- `index.php`: interfaz principal de la tienda.
- `assets/app.js`: catálogo, carrito y renderizado de Cajita de Pagos.
- `api/payphone-config.php`: entrega la configuración necesaria al SDK de la caja.
- `respuesta.php`: confirma la transacción y muestra el response del API.
- `config.php`: TOKEN y STORE ID.
