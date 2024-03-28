<?php
namespace App\Listeners;

use App\Events\TyreCheckedIn;
use App\Interfaces\TyreEvent;
use App\Models\History;

class TyreActionLogger
{
    public function handle(TyreEvent $event)
    {
        $actionDate = now();

        if ($event instanceof TyreCheckedIn) {
            $actionDate = $event->getCheckinDate();
        }

        History::create([
            'client_id' => $event->getClientId(),
            'tyre_id' => $event->getTyreId(),
            'action' => $event->getAction(),
            'action_date' => $actionDate
        ]);
    }
}
