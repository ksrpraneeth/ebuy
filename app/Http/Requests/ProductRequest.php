<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            if ($token = JWTAuth::parseToken()) {
                $user = JWTAuth::toUser($token);
                if ($user) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            return false;

        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price' => "required|gt:0",
            'description' => 'required'
        ];
    }
}
