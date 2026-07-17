<?php

class UserController {
    public function show(Request $request, $id){
      return new Response(200, [], "User with ID = $id");
    }
}