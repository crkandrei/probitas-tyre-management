<?php
namespace App\Listeners;

use App\Interfaces\TyreEvent;
use App\Models\History;

class TyreActionLogger
{
    public function handle(TyreEvent $event)
    {
        History::create([
            'client_id' => $event->getClientId(),
            'tyre_id' => $event->getTyreId(),
            'action' => $event->getAction(),
            'action_date' => now()
        ]);
    }
}
