<?php 

namespace App\Enums;

enum EmploymentType:string 
{
    case INTERNSHIP = "INTERNSHIP";
    case FULL_TIME = "FULL_TIME";
    case PART_TIME = "PART_TIME";
    case CONTRACT = "CONTRACT";
    case FREELANCE = "FREELANCE";

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}