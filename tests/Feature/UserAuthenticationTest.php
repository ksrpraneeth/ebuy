<?php

namespace Tests\Feature;

use App\Http\Requests\UserAuthRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @dataProvider provideUserLoginData
     * @param $shouldPass
     * @param $data
     * @param $message
     */
    public function user_should_provide_valid_data_to_login($shouldPass, $data, $message)
    {
        $rules = (new UserAuthRequest())->rules();
        $validator = Validator::make($data, $rules);
        $this->assertEquals($shouldPass, $validator->passes(), $message);
    }


    /**
     * @test
     */
    public function registered_user_should_be_able_to_authenticate_with_username_password()
    {
        $this->withoutExceptionHandling();
        $params = ['username' => 'admin', 'password' => 'test@123', 'dob' => '1193-12-01', 'full_name' => 'Praneeth Kalluri'];
        $this->post('api/register', $params);
        $response = $this->post('api/login', ['username' => $params['username'], 'password' => $params['password']]);
        $response->assertSuccessful();
        $response->assertJsonStructure(['status', 'data' => ['token', 'user'], 'message']);
        $user = User::where('username', $params['username'])->first();
        $this->assertAuthenticatedAs($user);
        $this->assertEquals(true,$response->json('status'));
        $this->assertEquals('Success',$response->json('message'));
    }

    /**
     * @test
     */
    public function user_should_not_be_able_to_authenticate_with_wrong_credentials(){
        $params = ['username' => 'admin', 'password' => 'test@123', 'dob' => '1193-12-01', 'full_name' => 'Praneeth Kalluri'];
        $this->post('api/register', $params);
        $response = $this->post('api/login', ['username' => $params['username'], 'password' => 'test']);
        $this->assertEquals(false,$response->json('status'));
        $this->assertEquals('Invalid Credentials',$response->json('message'));
        $response->assertSuccessful();
    }


    public function provideUserLoginData()
    {
        return [
            ['shouldPass' => false, 'data' => [], 'message' => 'Username and Password are required'],
            ['shouldPass' => false, 'data' => ['username' => 'admin'], 'message' => 'Password is required'],
            ['shouldPass' => false, 'data' => ['password' => 'test'], 'message' => 'Username is required'],
            ['shouldPass' => true, 'data' => ['username' => 'admin', 'password' => 'test'], 'message' => '']
        ];
    }
}
