<?php 

namespace App\Enums;

enum Religion:string 
{
    case HINDUISM = "HINDUISM";
    case ISLAM = "ISLAM";
    case CHRISTIANITY = "CHRISTIANITY";
    case SIKHISM = "SIKHISM";
    case BUDDHISM = "BUDDHISM";
    case JAINISM = "JAINISM";
    case OTHER = "OTHER";

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}