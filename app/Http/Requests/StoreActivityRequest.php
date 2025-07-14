<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'celeb_id' => 'required|string',
            'timestamp' => 'required|date',
            'type' => 'required|string', // e.g. instagram_post, imdb_event
            'summary' => 'required|string',
            'source_url' => 'nullable|url',
            'media' => 'nullable|array',
        ];
    }
}
