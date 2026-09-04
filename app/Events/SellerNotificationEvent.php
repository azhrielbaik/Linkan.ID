<?php

namespace App\Events;

use App\Models\User;
use App\Services\AdminSeller\DashboardService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellerNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sellerId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $sellerId)
    {
        $this->sellerId = $sellerId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('seller-notifications.' . $this->sellerId),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'notifications';
    }

    public function broadcastWith(): array
    {
        $seller = User::find($this->sellerId);
        
        if ($seller) {
            return app(DashboardService::class)->fetchSellerNotificationsData($seller);
        }

        return [];
    }
}
