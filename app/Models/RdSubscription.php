<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RdSubscription extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    function rd_plan(): HasOne {
        return $this->hasOne(RdPlan::class, 'id', 'rd_plan_id');
    }

    function rd_installments(): HasMany {
        return $this->hasMany(RdInstallment::class, 'rd_subscription_id', 'id');
    }
}
