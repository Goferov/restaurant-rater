<?php

namespace App;

class Request {
    private array $get;
    private array $post;
    private array $server;
    private array $file;

    public function __construct(array $get, array $post, array $server, array $file) {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->file = $file;
    }

    public function isPost(): bool {
        return $this->server['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool {
        return $this->server['REQUEST_METHOD'] === 'GET';
    }

    public function get(string $name, $default = null) {
        return $this->get[$name] ?? $default;
    }

    public function post(string $name, $default = null) {
        return $this->post[$name] ?? $default;
    }

    public function server(string $name) {
        return $this->server[$name] ?? null;
    }

    public function file(string $name) {
        return $this->file[$name] ?? null;
    }
}