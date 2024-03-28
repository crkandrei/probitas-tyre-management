<?php
namespace App\Events;

use App\Interfaces\TyreEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TyreCheckedOut implements TyreEvent
{
    use Dispatchable, SerializesModels;

    private int $clientId;
    private int $tyreId;

    public const ACTION = 'A facut check out';

    public function __construct($clientId, $tyreId)
    {
        $this->clientId = $clientId;
        $this->tyreId = $tyreId;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getTyreId()
    {
        return $this->tyreId;
    }

    public function getAction()
    {
        return self::ACTION;
    }
}
