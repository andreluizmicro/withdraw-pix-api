<?php

declare(strict_types=1);

namespace App\Infrastructure\Presentation\Http\V1\Request;

use App\Application\DTO\WithdrawPix\WithdrawPixInputDTO;
use App\Domain\Enum\PixType;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class CreateWithdrawPixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(array_column(PixType::cases(), 'value')),
            ],
            'key' => 'required|email',
        ];
    }

    public function toDto(): WithdrawPixInputDTO
    {
        return WithdrawPixInputDTO::fromArray(
            array_merge($this->validated(), [
                'account_id' => $this->route('accountId'),
            ])
        );
    }
}
