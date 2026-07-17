<?php

function view(string $template, array $data): Response {
  $path = __DIR__ . "/../Templates/{$template}.php";

  extract($data);

  if (file_exists($path)){
    ob_start();
    include($path);
    
    $html = ob_get_clean();

    return new Response(200, [], $html);
  }

  return new Response(404, [], "File {$template} not found");
}