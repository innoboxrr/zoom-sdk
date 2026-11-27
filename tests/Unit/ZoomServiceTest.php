<?php

namespace Innoboxrr\ZoomSdk\Tests\Unit;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Innoboxrr\ZoomSdk\Facades\Zoom;
use Innoboxrr\ZoomSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Las cuatro operaciones contra la API de Zoom, con las respuestas simuladas.
 *
 * No prueban que Zoom conteste lo que se espera: prueban que el paquete pide
 * el token con Server-to-Server OAuth y manda a cada endpoint lo que debe.
 */
final class ZoomServiceTest extends TestCase
{
    private const CREDENTIALS = [
        'account' => 'account-id',
        'client' => 'client-id',
        'secret' => 'client-secret',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        Http::fake([
            'zoom.us/oauth/token*' => Http::response(['access_token' => 'token-123', 'expires_in' => 3600]),
            'api.zoom.us/v2/users/me/meetings' => Http::response(['id' => 111, 'topic' => 'Clase']),
            'api.zoom.us/v2/meetings/111' => function (Request $request) {
                return $request->method() === 'GET'
                    ? Http::response(['id' => 111, 'topic' => 'Clase editada'])
                    : Http::response(null, 204);
            },
        ]);
    }

    #[Test]
    public function crea_una_reunion_con_las_credenciales_que_recibe(): void
    {
        $meeting = Zoom::createMeeting(self::CREDENTIALS + [
            'meeting' => ['topic' => 'Clase', 'password' => 'abc123'],
        ]);

        $this->assertSame(111, $meeting['id']);

        Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://zoom.us/oauth/token')
            && str_contains($request->url(), 'grant_type=account_credentials')
            && str_contains($request->url(), 'account_id=account-id')
            && $request->header('Authorization')[0] === 'Basic ' . base64_encode('client-id:client-secret'));

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.zoom.us/v2/users/me/meetings'
            && $request->method() === 'POST'
            && $request->hasHeader('Authorization', 'Bearer token-123')
            && $request['topic'] === 'Clase'
            && $request['password'] === 'abc123');
    }

    #[Test]
    public function toma_las_credenciales_de_la_configuracion_si_no_se_pasan(): void
    {
        config([
            'zoomsdk.account' => 'account-id',
            'zoomsdk.client' => 'client-id',
            'zoomsdk.secret' => 'client-secret',
        ]);

        $this->assertSame(111, Zoom::getMeeting('111')['id']);

        Http::assertSent(fn (Request $request) => str_contains($request->url(), 'account_id=account-id'));
    }

    #[Test]
    public function sin_credenciales_lanza_una_excepcion(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Zoom::getMeeting('111');
    }

    /**
     * Antes updateMeeting() no pasaba $config['meeting'] a la API: mandaba un
     * PATCH vacio y devolvia la reunion sin cambios.
     */
    #[Test]
    public function actualizar_manda_los_cambios_de_la_reunion(): void
    {
        $meeting = Zoom::updateMeeting('111', self::CREDENTIALS + [
            'meeting' => ['topic' => 'Clase editada'],
        ]);

        $this->assertSame('Clase editada', $meeting['topic']);

        Http::assertSent(fn (Request $request) => $request->method() === 'PATCH'
            && $request->url() === 'https://api.zoom.us/v2/meetings/111'
            && $request->data() === ['topic' => 'Clase editada']);
    }

    #[Test]
    public function elimina_una_reunion(): void
    {
        $this->assertTrue(Zoom::deleteMeeting('111', self::CREDENTIALS));

        Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
            && $request->url() === 'https://api.zoom.us/v2/meetings/111');
    }

    #[Test]
    public function la_configuracion_se_publica_con_la_etiqueta_del_readme(): void
    {
        $this->assertNotEmpty(\Illuminate\Support\ServiceProvider::pathsToPublish(null, 'zoomsdk-config'));
    }
}
