<?php

namespace Tests\Feature;

use App\Http\Requests\UserRegRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @dataProvider provideUserRegData
     * @param $shouldPass
     * @param $data
     * @param $message
     */
    public function user_should_have_valid_data_to_register($shouldPass, $data, $message)
    {
        $rules = (new UserRegRequest())->rules();
        $validator = Validator::make($data, $rules);
        $this->assertEquals($shouldPass, $validator->passes(), $message);
    }

    /**
     * @test
     */
    public function user_should_be_able_register_with_valid_data()
    {
        $this->withoutExceptionHandling();

        $params = ['username' => 'admin', 'password' => 'test@123', 'dob' => '1193-12-01', 'full_name' => 'Praneeth Kalluri'];
        $response = $this->post('api/register', $params);
        $response->assertOk();
        $user = User::all()->toArray();
        $this->assertCount(1, $user);
        $this->assertTrue(Hash::check($params['password'], $user[0]['password']));
        $this->assertTrue($response->json(['status']));
        $this->assertEquals($user[0],$response->json(['data']));
    }

    /**
     * @test
     */
    public function user_should_be_unique(){
        $params = ['username' => 'admin', 'password' => 'test@123', 'dob' => '1193-12-01', 'full_name' => 'Praneeth Kalluri'];
        $response = $this->post('api/register', $params);
        $params = ['username' => 'admin', 'password' => 'test@123', 'dob' => '1193-12-01', 'full_name' => 'Praneeth Kalluri'];
        $response = $this->post('api/register', $params);
        $response->assertSessionHasErrors('username');
    }


    public function provideUserRegData()
    {
        return [
            ['shouldPass' => false, [], 'message' => 'User should have Username,Password,DOB,Full Name'],
            ['shouldPass' => false, ['username' => 'admin'], 'message' => 'User should have Password,DOB,Full Name'],
            ['shouldPass' => false, ['username' => 'admin', 'password' => 'test@123'], 'message' => 'User should have DOB,Full Name'],
            ['shouldPass' => false, ['username' => 'admin', 'password' => 'test@123', 'dob' => '1993-12-01'], 'message' => 'User should have Full Name'],
            ['shouldPass' => false, ['username' => 'admin', 'password' => 'test@123', 'dob' => '1342434235', 'full_name' => 'Praneeth Kalluri'], 'message' => 'Date of birth should be date'],
            ['shouldPass' => false, ['username' => 'admin', 'password' => 'test@123', 'dob' => '1342434235', 'full_name' => 'Praneeth Kalluri23423'], 'message' => 'Full Name should not contain numbers'],
            ['shouldPass' => true, ['username' => 'admin', 'password' => 'test@123', 'dob' => '1993-12-01', 'full_name' => 'Praneeth Kalluri'], 'message' => 'All details are valid']
        ];
    }

}
