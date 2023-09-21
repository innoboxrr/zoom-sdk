<?php

namespace Innoboxrr\ZoomSdk\Support;

/**
 * SetupConfig - Clase responsable de la gestión y validación de configuraciones.
 */
class SetupConfig
{
    /**
     * @var array La configuración proporcionada.
     */
    protected $config;

    /**
     * @var array Las claves requeridas para la configuración.
     */
    protected $requiredKeys;

    /**
     * @var array Mensajes personalizados de error.
     */
    protected $errorMessages;

    /**
     * Constructor de la clase SetupConfig.
     *
     * @param array $config          La configuración a gestionar.
     * @param array $requiredKeys    Las claves requeridas para la configuración.
     * @param array $errorMessages   Mensajes de error personalizados.
     */
    public function __construct(array $config, array $requiredKeys = [], array $errorMessages = [])
    {
        $this->config = $config;
        $this->requiredKeys = $requiredKeys;
        $this->errorMessages = $errorMessages;

        $this->validateConfig();
    }

    /**
     * Valida que la configuración contenga todas las claves requeridas.
     */
    protected function validateConfig(): void
    {
        foreach ($this->requiredKeys as $key) {
            $this->validateKey($key);
        }
    }

    /**
     * Verifica si una clave específica está presente en la configuración.
     *
     * @param string $key La clave a verificar.
     *
     * @throws \InvalidArgumentException Si la clave no está presente.
     */
    protected function validateKey(string $key): void
    {
        if (!isset($this->config[$key])) {
            throw new \InvalidArgumentException($this->getErrorMessage($key));
        }
    }

    /**
     * Obtiene el mensaje de error asociado a una clave. 
     * Si no hay un mensaje personalizado, se devuelve un mensaje predeterminado.
     *
     * @param string $key La clave para la que se requiere el mensaje de error.
     *
     * @return string El mensaje de error.
     */
    protected function getErrorMessage(string $key): string
    {
        $defaultMessage = "Se requiere el '{$key}' en la configuración.";
        return $this->errorMessages[$key] ?? $defaultMessage;
    }

    public function getData(): array
    {
        return $this->config;
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
        $this->config = array_merge($this->config, $newConfig);
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

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        if(count($args) > 0) {

            $default = $args[0];

            return $default;

        }

        throw new \InvalidArgumentException("La clave '{$key}' no existe en la configuración.");
    }
}
