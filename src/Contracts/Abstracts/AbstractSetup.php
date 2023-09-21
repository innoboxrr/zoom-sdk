<?php

namespace Innoboxrr\ZoomSdk\Contracts\Abstracts;

use Innoboxrr\ZoomSdk\Support\SetupConfig;
use Illuminate\Support\Arr;

/**
 * AbstractSetup - Clase base abstracta para la configuración inicial de pasarelas de pago.
 */
abstract class AbstractSetup 
{
    /**
     * @var SetupConfig Instancia del objeto SetupConfig para la gestión de configuraciones.
     */
    protected $config;

    /**
     * Constructor de la clase AbstractSetup.
     *
     * @param array $config          Configuración específica proporcionada por el usuario.
     * @param array $requiredKeys    Claves que son obligatorias dentro de la configuración.
     * @param array $errorMessages   Mensajes personalizados para errores en la validación de configuración.
     */
    public function __construct(array $config, array $requiredKeys = [], array $errorMessages = [])
    {
        $this->config = new SetupConfig($config, $requiredKeys, $errorMessages);
    }

    /**
     * Merge the current configuration with the provided configuration.
     *
     * @param array $newConfig The new configuration to merge.
     *
     * @return void
     */
    public function merge(array $newConfig) 
    {
        $this->config->merge($newConfig);
    }

    /**
     * Retrieve a specific configuration value by its key.
     *
     * @param string $key The key for which the configuration value is to be retrieved.
     *
     * @return mixed The configuration value associated with the provided key, or null if the key doesn't exist.
     */
    public function config($key) 
    {
        return Arr::get($this->config->getData(), $key);
    }

    /**
     * Método mágico que se invoca cuando se llama a un método que no está definido en esta clase.
     * Se utiliza para obtener dinámicamente valores de la configuración.
     *
     * @param string $method El nombre del método invocado.
     * @param array  $args   Los argumentos proporcionados al método.
     *
     * @throws \InvalidArgumentException Si la clave solicitada no existe en la configuración.
     *
     * @return mixed El valor asociado con la clave solicitada.
     */
    public function __call($method, $args)
    {

        // Convertir getSomeKey a some_key
        $key = lcfirst(preg_replace('/^get/', '', $method));
        $key = strtolower(preg_replace('/[A-Z]/', '_$0', $key));

        $data = $this->config->getData();

        if (isset($data[$key])) {
            return $data[$key];
        }

        if(count($args) > 0) {

            $default = $args[0];

            return $default;

        }

        throw new \InvalidArgumentException("La clave '{$key}' no existe en la configuración.");
    }

}
