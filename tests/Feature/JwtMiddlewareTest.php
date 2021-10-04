<?php

namespace Tests\Feature;

use App\Http\Middleware\JwtMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Tymon\JWTAuth\Facades\JWTAuth;


class JwtMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function jwt_middleware_should_send_error_if_no_token()
    {
        $request = new Request();
        $jwtMiddleware = new JwtMiddleware();
        $response = $jwtMiddleware->handle($request, function ($res) {
        });
        $this->assertEquals($response->getStatusCode(), 401);

    }

    /**
     * @test
     */
    public function jwt_middleware_should_send_error_if_wrong_token()
    {

        $request = new Request();
        $jwtMiddleware = new JwtMiddleware();
        $request->headers->set("Authorization", "Bearer {asfdlajsfl}");
        $response = $jwtMiddleware->handle($request, function () {
        });
        $this->assertEquals($response->getStatusCode(), 401);

    }

    /**
     * @test
     */
    public function jwt_middleware_should_authenticate_if_correct_token()
    {
        $request = new Request();
        $jwtMiddleware = new JwtMiddleware();
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $request->headers->set("Authorization", "Bearer {$token}");
        $response = $jwtMiddleware->handle($request, function () {
        });
        $this->assertNull($response);
    }

}
