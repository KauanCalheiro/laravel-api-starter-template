<?php

namespace App\Clients;

use Exception;
use SoapClient;

abstract class BaseSoapClient
{
    protected SoapClient $client;
    protected string $configKey;
    protected array $configs;
    protected string $key;

    public function __construct()
    {
        $this->validate();
        $this->processConfigs();

        try {
            $this->client = new SoapClient(null, $this->configs);
        } catch (Exception $e) {
            throw new Exception('Erro ao inicializar SoapClient: ' . $e->getMessage());
        }
    }

    private function validate(): void
    {
        if (empty($this->configKey)) {
            throw new Exception('Chave de configuração não definida.');
        }
    }

    private function processConfigs(): void
    {
        $this->configs = config($this->configKey);

        $this->key = $this->configs['key'];
        unset($this->configs['key']);
    }

    protected function call(string $method, array $arguments = []): mixed
    {
        if (! method_exists($this->client, $method) && ! is_callable([$this->client, '__soapCall'])) {
            throw new Exception("Método '$method' não encontrado no serviço SOAP.");
        }

        $arguments[] = $this->key;

        $result = $this->client->__soapCall($method, $arguments);
        $result = unserialize(base64_decode($result));

        if ($result instanceof Exception) {
            throw new Exception("Erro ao chamar o método '$method': " . $result->getMessage());
        }

        return $result;
    }
}
