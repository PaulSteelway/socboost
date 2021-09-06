<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\TplDefaultLang;
use App\Models\TplTranslation;

/**
 * Class TranslationPublishedEvent
 * @package App\Events
 */
class TranslationPublishedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $translation;
    /**
     * Create a new event instance.
     *
     * @param  TplTranslation $translation
     * @return void
     */
    public function __construct(TplTranslation $translation)
    {
        //
        $this->translation = $translation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
