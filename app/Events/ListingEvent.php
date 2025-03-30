<?php

namespace App\Events;

use App\Data\Listing\ListingData;
use App\Models\Listing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListingEvent implements ShouldBroadcast
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public $listing;

    public function __construct(Listing $listing)
    {
        $this->listing = collect(ListingData::from($listing->load('item'))->toArray())
            ->except('canManage', 'item.isFavorite')
            ->all();
    }

    public function broadcastOn()
    {
        return new Channel('listings');
    }

    public function broadcastWith()
    {
        return $this->listing;
    }
}
