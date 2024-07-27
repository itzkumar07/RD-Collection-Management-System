<?php 

namespace App\Enums;

enum PaymentStatus:string 
{
    case PENDING = "PENDING";
    case FAILED = "FAILED";
    case COMPLETED = "COMPLETED";

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}