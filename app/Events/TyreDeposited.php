<?php
namespace App\Events;

use App\Interfaces\TyreEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TyreDeposited implements TyreEvent
{
    use Dispatchable, SerializesModels;

    private int $clientId;
    private int $tyreId;
    private int $row;
    private int $column;
    private int $shelf;
    private string $action = 'Au fost depozitate';

    public function __construct($clientId, $tyreId, $row, $column, $shelf)
    {
        $this->clientId = $clientId;
        $this->tyreId = $tyreId;
        $this->row = $row;
        $this->column = $column;
        $this->shelf = $shelf;
        $this->action= $this->action . ' in randul ' . $row . ' coloana ' . $column . ' raftul ' . $shelf;
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
        return $this->action;
    }
}
