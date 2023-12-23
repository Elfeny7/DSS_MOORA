<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alternative extends Model
{
    use HasFactory;

    public function criterias(): BelongsToMany
    {
        return $this->belongsToMany(Criteria::class);
    }
}
