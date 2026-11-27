# Changelog

Todas las modificaciones notables a este proyecto serán documentadas en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
y este proyecto sigue [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-11-27

Primera versión etiquetada. Hasta ahora sólo existía `dev-master`, que pedía
`innoboxrr/larapack-generator ^3.2` en `require`: cualquier aplicación con el
generador 9 no podía instalar el paquete.

### Cambiado

- **Laravel 13 y PHP 8.3.** Se declaran `php ^8.3` e `illuminate/http`,
  `illuminate/support` e `illuminate/validation` `^13.0`, que son las piezas
  que el código usa (cliente HTTP, validación, fachadas). Antes no declaraba
  ni PHP ni Laravel.
- **`larapack-generator` pasa a `require-dev`, en `^9.0`.** El paquete no usa
  ninguna clase del generador en tiempo de ejecución, sólo `./builder` para
  generar. En `require` arrastraba la 3.x a la aplicación y chocaba con la
  línea base del ecosistema.
- **Los proveedores heredan de `ServiceProvider`**, no de los de Foundation.
  El `RouteServiceProvider` de Foundation volvía a cargar las rutas de la
  aplicación una vez por paquete, y el `EventServiceProvider` registraba otra
  vez el correo de verificación. El descubrimiento de eventos ya no recorre la
  raíz del disco cuando la carpeta `Http/Events` no existe.
- **Licencia `MIT`** en `composer.json`, como dice el README; antes decía `+`.

### Eliminado

- **La ruta `GET /zoom/test`.** Creaba una reunión real con las credenciales
  de la aplicación y la volcaba con `dd()`, sin autenticación, a cualquiera que
  la abriera. El paquete ya no registra rutas.

### Corregido

- **`Zoom::updateMeeting()` no actualizaba nada.** No pasaba
  `$config['meeting']` a la API: mandaba un `PATCH` vacío y devolvía la reunión
  sin cambios.
- **`vendor:publish --tag=zoomsdk-config`**, la orden del README, no publicaba
  nada: la publicación estaba comentada. Ahora publica `config/zoomsdk.php`
  con esa etiqueta y con `config`.

### Añadido

- Suite de pruebas: el paquete arranca en Laravel 13 (proveedores, clases,
  rutas, sin tabla de caché, sin duplicar rutas ni listeners de la aplicación)
  y las cuatro operaciones contra la API de Zoom con respuestas simuladas.
- CI con los flujos reutilizables de `innoboxrr/.github`: pruebas y
  `larapack:audit` en cada push y pull request, y etiqueta a partir de
  `VERSION` cuando pasan.
