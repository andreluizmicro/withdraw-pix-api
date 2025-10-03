<?php

declare(strict_types=1);

namespace App\Infrastructure\Presentation\Http\V1\Request;

use App\Application\DTO\Account\CreateAccountInputDTO;
use Hyperf\Validation\Request\FormRequest;

class CreateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 3 characters.',
            'name.max' => 'The name may not be greater than 255 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'name',
        ];
    }

    public function toDto(): CreateAccountInputDTO
    {
        return CreateAccountInputDTO::fromArray(
            $this->validated(),
        );
    }
}
