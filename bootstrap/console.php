<?php

$app = new ConsoleApplication();
$app->add(new ServeCommand());

return $app;