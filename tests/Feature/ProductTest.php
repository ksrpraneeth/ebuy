<?php

namespace Tests\Feature;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_should_be_able_to_save_product()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/product/create', ['description' => 'Pen', 'price' => '20']);
        $response->assertOk();
        $this->assertCount(1, Product::all());
    }

    /**
     * @test
     * @dataProvider productDataProvider
     * @param $shouldPass
     * @param $data
     * @param $message
     */

    public function input_data_should_be_valid($shouldPass,$data,$message){

        $rules = (new ProductRequest())->rules();
        $validator = Validator::make($data,$rules);
        $this->assertEquals($shouldPass,$validator->passes(),$message);
    }

    public function productDataProvider()
    {
        return [
            ['shouldPass' => false, [],'message'=>'Description and Price are required'],
            ['shouldPass' => false, ['description' => 'Pen'],'message'=>'Price is required'],
            ['shouldPass' => false, ['price' => '20'],'message'=>'Description is required'],
            ['shouldPass' => false, ['description' => 'Pen', 'price' => '0'],'message'=>'Price should be greater than zero'],
            ['shouldPass' => false, ['description' => 'Pen', 'price' => '-1'],'message'=>'Price should be greater than zero'],
            ['shouldPass' => true, ['description' => 'Pen', 'price' => '20'],'message'=>'All fields are valid'],
        ];
    }

}
