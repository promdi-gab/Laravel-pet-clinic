<?php

namespace App\Http\Requests;

use App\Models\Adopter;
use Illuminate\Foundation\Http\FormRequest;

class adopterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
