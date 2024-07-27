<?php 

namespace App\Enums;

enum PaymentMethod:string 
{
    case UPI = "UPI";
    case CASH = "CASH";
    case BANK_TRANSFER = "BANK_TRANSFER";
    case CHEQUE = "CHEQUE";
    case DRAFT = "DRAFT";
    case PREPAID = "PREPAID";

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}