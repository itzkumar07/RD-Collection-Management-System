<?php 

namespace App\Enums;

enum MaritalStatus:string 
{
    case SINGLE = "SINGLE";
    case MARRIED = "MARRIED";
    case DIVORCE = "DIVORCE";
    case OTHER = "OTHER";

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}