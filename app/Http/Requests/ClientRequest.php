<?php

namespace App\Http\Requests;

use App\Rules\PhoneRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'services' => $this->transformServices($this->services_selected),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('clients')
                    ->where(function (Builder $query) {
                        return $query->where('user_id', auth()->user()->id);
                    })
                    ->ignore($this->id ?? null),
            ],
            'phone' => ['required', 'string', new PhoneRule],
            'services' => ['required'],
        ];
    }

    // Formatando serviços para salvar da forma correta
    private function transformServices(?array $services = [])
    {
        if (!empty($services)) {
            return collect($services)
                ->map(function ($service) {
                    return $service['code'];
                })
                ->toArray();
        }
        return [];
    }
}
