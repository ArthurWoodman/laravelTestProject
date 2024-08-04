<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceOf User && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method === 'PUT') {
            return [
                'title' => 'required|min:3',
                'slug' => 'required|min:3',
                'body' => 'required|min:3',
                'publication_date' => 'required|date',
            ];
        } else {
            return [
                'title' => ['sometimes', 'required', 'min:3'],
                'slug' => ['sometimes', 'required', 'min:3'],
                'body' => ['sometimes', 'required', 'min:3'],
                'publication_date' => ['sometimes', 'required', 'date'],
            ];
        }
    }
}
