# Zoom SDK for Laravel

Este paquete proporciona una integración fácil y rápida con la API de Zoom para aplicaciones Laravel.

## Requisitos

- PHP >= 7.4
- Laravel >= 8.x

## Instalación

```bash
composer require innoboxrr/zoom-sdk
```

## Configuración

1. Publica el archivo de configuración:

```bash
php artisan vendor:publish --tag=zoomsdk-config
```

2. (Opcional) Configura tus credenciales en el archivo `.env`:

El paquete tiene la flexibilidad de que puedas ocupar estas credenciales para todas las solicitudes o pasar estos parámetros de manera dinámica al realizar una solicitud.

``` bash
ZOOM_TIMEZONE=default_timezone
ZOOM_ACCOUNT=your_account
ZOOM_CLIENT=your_client
ZOOM_SECRET=your_secret
```

## Uso básico. Credenciales estáticas

### Crear una reunión

```php
$meeting = Zoom::createMeeting();
```

También tiene la opción de añadir parámetros pasando un arreglo con la estrucutra siguiente:

```php
$meeting = Zoom::createMeeting([
	'meeting' => [
		'topic' => 'Título de la reunión',
		'start_time' => now()->format('Y-m-d\TH:i:s'),
		'duration' => '60',
		'timezone' => 'America/Mexico_City' // Consulta \Innoboxrr\ZoomSdk\Support\Constants para ver los posibles valores
		'password' => '126816' // Hasta 10 caracteres
	]
]);
```

### Obtener una reunión

```php
$meeting = Zoom::getMeeting('MEETING_ID');
```

### Actualizar una reunión

```php
$updatedMeeting = Zoom::updateMeeting('MEETING_ID', $config);
```

### Eliminar una reunión

```php
Zoom::deleteMeeting('MEETING_ID');
```

## Uso básico. Credenciales dinámica

### Crear una reunión

```php
$meeting = Zoom::createMeeting([
	'account' => 'zoom_account',
	'client' => 'zoom_client',
	'secret' => 'zoom_secret',
	'meeting' => [
		'topic' => 'Título de la reunión',
		'start_time' => now()->format('Y-m-d\TH:i:s'),
		'duration' => '60',
		'timezone' => 'America/Mexico_City' // Consulta \Innoboxrr\ZoomSdk\Support\Constants para ver los posibles valores
		'password' => '126816' // Hasta 10 caracteres
	]
]);
```

En este caso igual el parámetro 'meeting' del arreglo, es opcional

### Obtener una reunión

```php

$config = [
	'account' => 'zoom_account',
	'client' => 'zoom_client',
	'secret' => 'zoom_secret'
];

$meeting = Zoom::getMeeting('MEETING_ID', $config);
```

### Actualizar una reunión

```php
$config = [/*...*/];

$updatedMeeting = Zoom::updateMeeting('MEETING_ID', $config);
```

### Eliminar una reunión

```php
$config = [/*...*/];

Zoom::deleteMeeting('MEETING_ID', $config);
```

### Nota

Tenga en cuenta que si los parámetros de account, cliente, secret no se proporcionan de manera estática, todos debe estár presentes en la forma dinámica, de lo contrario se lanzará una excepción.

## Contribuciones

Las contribuciones son bienvenidas. Por favor, envía PRs para cualquier mejora. Asegúrate de que los tests unitarios cubran la nueva funcionalidad o las correcciones.

## Licencia

Este paquete está licenciado bajo la licencia MIT.
