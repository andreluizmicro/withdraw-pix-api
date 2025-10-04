<?php

declare(strict_types=1);

namespace App\Infrastructure\Presentation\Http\V1\Request;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Enum\WithdrawMethod;
use Carbon\Carbon;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $now = Carbon::now()->toDateTimeString();
        $maxDate = Carbon::now()->addDays(7)->toDateTimeString();

        return [
            'method' => [
                'required',
                'string',
                Rule::in(array_column(WithdrawMethod::cases(), 'value')),
            ],
            'pix.type' => 'required|string|in:email',
            'pix.key' => 'required|email',
            'amount' => 'required|numeric|min:0.01',
            'schedule' => [
                'nullable',
                'date',
                'after_or_equal:' . $now,
                'before_or_equal:' . $maxDate,
            ],
        ];
    }

    public function messages(): array
    {
        $withdrawMethods = array_column(WithdrawMethod::cases(), 'value');

        return [
            'method.required' => 'O método é obrigatório.',
            'method.in' => 'Atualmente só são permitidos os métodos '. implode(', ', $withdrawMethods),

            'pix.type.required' => 'O tipo de chave PIX é obrigatório.',
            'pix.type.in' => 'Atualmente só aceitamos chave PIX do tipo email.',
            'pix.key.required' => 'A chave PIX é obrigatória.',
            'pix.key.email' => 'A chave PIX deve ser um email válido.',

            'amount.required' => 'O valor do saque é obrigatório.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor mínimo de saque é R$ 0,01.',

            'schedule.date' => 'A data deve ser válida.',
            'schedule.after_or_equal' => 'Não é permitido agendar para o passado.',
            'schedule.before_or_equal' => 'Não é permitido agendar para mais de 7 dias no futuro.',
        ];
    }

    public function toDto(): CreateWithdrawInputDTO
    {
        return CreateWithdrawInputDTO::fromArray(
            array_merge($this->validated(), [
                'account_id' => $this->route('accountId'),
            ])
        );
    }
}
