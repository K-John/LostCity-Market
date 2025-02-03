<?php

namespace App\Data\Token;

use Spatie\LaravelData\Data;

class TokenFormData extends Data
{
    public function __construct(
        public string $token,
    ) {
    }

    public static function rules(): array
    {
        return [
            'token' => ['string', 'required']
        ];
    }
}