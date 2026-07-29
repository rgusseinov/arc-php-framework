<?php

interface ServiceProviderInterface {
    public function register(Container $container): void;

    public function boot(Container $container): void;
}