<?php

interface RequestHandlerInterface {
    public function handle(Request $request): Response;
}