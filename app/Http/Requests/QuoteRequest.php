<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [    
           'destino' => 'required|in:NACIONAL,AMERICAS,EUROPA',
           'data_inicio' => 'required|date',
           'data_fim' => [
            'required',
            'date',
            'after_or_equal:data_inicio'
           ],
           'viajantes' => 'required|array|min:1',
           'viajantes.*.nome' => 'required|string|',
           'viajantes.*.data_nascimento' => 'required|date',
           'viajantes.*.adicionais' => 'nullable|array',  
        ];
    }
}
