<?php

namespace App\Data\Banner;

use App\Enums\BannerDisplayScope;
use App\Enums\BannerType;
use Illuminate\Contracts\Validation\Validator;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class BannerFormData extends Data
{
    public function __construct(
        public ?int $id,
        public BannerType $type,
        public string $message,
        public bool $is_active = true,
        public ?string $start_at,
        public ?string $end_at,
        /** @var DataCollection<\App\Data\Item\ItemFormData> */
        public ?DataCollection $items,
        public ?BannerDisplayScope $display_scope = null
    ) {
        $this->display_scope = $this->display_scope ?? (!empty($this->items) && $this->items->count() > 0 ? BannerDisplayScope::Item : BannerDisplayScope::Global);
    }

    public static function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:5', 'max:255'],
            'type' => ['required', 'string', 'in:default,warning,info,success,error'],
            'is_active' => ['required', 'boolean'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after:start_at'],
        ];
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $display_scope = $validator->getData()['display_scope'] ?? null;
            $items = $validator->getData()['items'] ?? [];

            if ($validator->errors()->any()) {
                return;
            }

            // If scope is global, ensure that items are not provided
            if ($display_scope === BannerDisplayScope::Global->value && !empty($items)) {
                $validator->errors()->add('items', 'Items are not allowed when display scope is global.');
            }

            // If scope is item, ensure that items are provided
            if ($display_scope === BannerDisplayScope::Item->value && empty($items)) {
                $validator->errors()->add('items', 'Items are required when display scope is item.');
            }

            // Ensure no duplicate items
            if ($items) {
                $itemIds = array_column($items, 'id');
                if (count($itemIds) !== count(array_unique($itemIds))) {
                    $validator->errors()->add('items', 'Duplicate items are not allowed.');
                }
            }
        });
    }
}