<?php
namespace App\Interfaces;

interface TyreEvent
{
    public function getClientId();
    public function getTyreId();
    public function getAction();
}
