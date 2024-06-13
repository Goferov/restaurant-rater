<?php

namespace App;

interface IRouter
{
    public function get(string $url, string $controller);
    public function post(string $url, string $controller);
    public function run(string $path);
}