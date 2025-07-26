<?php

namespace App\Clients;

use Exception;

abstract class CoreSoapClient extends BaseSoapClient
{
    protected ?string $model   = null;
    protected ?string $service = null;
    protected ?string $control = null;

    protected ?string $method = null;
    protected ?array $params  = null;

    protected function __construct()
    {
        parent::__construct();
    }

    protected static function make($key, $class): self
    {
        $instance       = new static();
        $instance->$key = $class;
        return $instance;
    }

    public static function model(string $model): self
    {
        return static::make('model', $model);
    }

    public static function service(string $service): self
    {
        return static::make('service', $service);
    }

    public static function control(string $control): self
    {
        return static::make('control', $control);
    }

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function params(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function run(): mixed
    {
        $this->validateConfiguration();

        $method = match (true) {
            ! empty($this->model)   => 'executaMetodoModel',
            ! empty($this->service) => 'executaMetodoService',
            ! empty($this->control) => 'executaMetodoControl',
        };

        return $this->call(
            $method,
            [
                $this->model ?: $this->service ?: $this->control,
                $this->method,
                $this->params ?: [],
            ],
        );
    }

    private function validateConfiguration(): void
    {
        if (empty($this->model) && empty($this->service) && empty($this->control)) {
            throw new Exception('Modelo, serviço ou controle não definidos.');
        }

        if (count(array_filter([$this->model, $this->service, $this->control])) > 1) {
            throw new Exception('Apenas um entre modelo, serviço ou controle pode ser definido.');
        }

        if (empty($this->method)) {
            throw new Exception('Método não definido.');
        }
    }
}
