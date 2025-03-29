<?php

use App\Broadcasting\ListingChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('listings', ListingChannel::class);
