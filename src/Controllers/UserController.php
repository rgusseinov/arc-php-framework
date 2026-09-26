<?php

namespace Arc\Framework\Controllers;

use Arc\Framework\Http\Request;
use Arc\Framework\Http\Response;

class UserController {
    public function show(Request $request, $id){
      return new Response(200, [], "User with ID = $id");
    }
}