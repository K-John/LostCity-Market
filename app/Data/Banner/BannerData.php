<?php

namespace App\Data\Banner;

use App\Enums\BannerDisplayScope;
use App\Enums\BannerType;
use DateTime;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class BannerData extends Data
{
    #[Computed]
    public BannerDisplayScope $displayScope;

    public function __construct(
        public int $id,
        public BannerType $type,
        public string $message,
        public bool $isActive,
        public ?DateTime $startAt,
        public ?DateTime $endAt,
        /** @var DataCollection<\App\Data\Item\ItemData> */
        public ?DataCollection $items = null,
        
    ) {
        $this->displayScope = $this->determineDisplayScope($id);
    }

    private function determineDisplayScope(): BannerDisplayScope
    {
        return (!empty($this->items) && $this->items->count() > 0 ? BannerDisplayScope::Item : BannerDisplayScope::Global);
    }
}
