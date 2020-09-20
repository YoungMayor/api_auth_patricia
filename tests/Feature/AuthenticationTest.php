<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testInvalid()
    {
        $email = "dummy.email".uniqid()."@mailer.com";

        //register with invalid credentials
        $register_response = $this->json('POST', '/api/register', [
            'email' => $email, 
            'password' => "password1", 
            'password_confirmation' => "password2", 
            'name' => "Dummy Test"
        ]);
        $register_response->assertStatus(422);

        // login with invalid credentials
        $login_response = $this->json('POST', '/api/login', [
            'email' => $email, 
            'password' => 'wrong password'
        ]);
        $login_response->assertStatus(401);

        // get user details when not logged in
        $user_response = $this->json('GET', '/api/user');
        $user_response->assertStatus(401);

    }

    public function testValid()
    {
        $email = "dummy.email".uniqid()."@mailer.com";

        //register with valid credentials
        $register_response = $this->json('POST', '/api/register', [
            'email' => $email, 
            'password' => "password", 
            'password_confirmation' => "password", 
            'name' => "Dummy Test"
        ]);
        $register_response->assertStatus(201);

        // login with valid credentials registered above
        $login_response = $this->json('POST', '/api/login', [
            'email' => $email, 
            'password' => 'password'
        ]);
        $login_response->assertStatus(200);
        $login_content = $login_response->decodeResponseJson();
        $auth_token = $login_content['token'];

        // get user details when logged in
        $user_response = $this->json('GET', '/api/user', [], [
            'Authorization' => "Bearer $auth_token"
        ]);
        $user_response->assertStatus(200);

    }
}
