<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'id_company',
        'amount',
        'date',
        'description',
        'tax_deductible',
        'is_recurring',
        'recurrence_frequency',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
