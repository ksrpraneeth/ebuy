<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{

    /**
     * @test
     * @dataProvider provideUserRegData
     * @param $shouldPass
     * @param $data
     * @param $message
     */
    public function user_should_have_valid_data_to_register($shouldPass, $data, $message)
    {
        
    }

    public function provideUserRegData()
    {
        return [
            ['shouldPass' => false, [], 'message' => 'User should have Username,Password,DOB,Full Name'],
            ['shouldPass' => false, ['username' => 'admin'], 'message' => 'User should have Password,DOB,Full Name'],
            ['shouldPass' => false, ['username' => 'admin', 'password' => 'test@123'], 'message' => 'User should have DOB,Full Name'],
            ['shouldPass' => false, ['username' => 'admin', 'password' => 'test@123', 'dob' => '1993-12-01'], 'message' => 'User should have Full Name'],
            ['shouldPass' => true, ['username' => 'admin', 'password' => 'test@123', 'dob' => '1993-12-01', 'full_name' => 'Praneeth Kalluri'], 'message' => 'All details are valid']
        ];
    }
}
