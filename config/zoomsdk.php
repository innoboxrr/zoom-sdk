<?php

return [

	'user_class' => 'App\Models\User',

	'excel_view' => 'innoboxrrzoomsdk::excel.',

	'notification_via' => ['mail', 'database'],

	'export_disk' => 's3',

	'timezone' => env('ZOOM_TIMEZONE', 'America/Mexico_City'),

	'account' => env('ZOOM_ACCOUNT', NULL), // Esto no lo he probado pero ver si funciona despupes

	'client' => env('ZOOM_CLIENT', NULL), // Esto no lo he probado pero ver si funciona despupes

	'secret' => env('ZOOM_SECRET', NULL), // Esto no lo he probado pero ver si funciona despupes	
];