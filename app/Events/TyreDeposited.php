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
    private ?int $column;
    private ?int $shelf;
    private ?string $observations;
    private string $action;

    public function __construct($clientId,
        $tyreId,
        $row,
        $column,
        $shelf,
        $observations
    ) {
        $this->clientId = $clientId;
        $this->tyreId = $tyreId;
        $this->row = $row;
        $this->column = $column;
        $this->shelf = $shelf;
        $this->observations = $observations;
        $this->action = $this->generateActionLog($row, $column, $shelf, $observations);
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getTyreId(): int
    {
        return $this->tyreId;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    private function generateActionLog($row, $column, $shelf, $observations): string
    {
        $stringAction = 'Au fost depozitate';
        if($row){
            $stringAction = $stringAction . ' in randul ' . $row;
        }
        if($column){
            $stringAction = $stringAction . ' coloana ' . $column;
        }
        if($shelf){
            $stringAction = $stringAction . ' raftul ' . $shelf;
        }
        if($observations){
            $stringAction = $stringAction . ' observatii : ' . $observations;
        }

        return $stringAction;
    }
}
