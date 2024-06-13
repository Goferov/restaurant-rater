<?php

namespace  App;

interface IContainer
{
    public function set($id, $factory);
    public function get($id);
    public function build(string $class);
    public function callMethod($object, string $method, array $parameters = []);
}