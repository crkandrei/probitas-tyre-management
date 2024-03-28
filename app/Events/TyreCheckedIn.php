<?php
namespace App\Events;

use App\Interfaces\TyreEvent;
use DateTime;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TyreCheckedIn implements TyreEvent
{
    use Dispatchable, SerializesModels;

    private int $clientId;
    private int $tyreId;
    private string $checkinDate;

    public const ACTION = 'A facut check In';

    public function __construct($clientId, $tyreId, $checkinDate)
    {
        $this->clientId = $clientId;
        $this->tyreId = $tyreId;
        $this->checkinDate = $checkinDate;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getTyreId()
    {
        return $this->tyreId;
    }

    public function getCheckinDate()
    {
        return $this->checkinDate;
    }

    public function getAction()
    {
        return self::ACTION;
    }
}
