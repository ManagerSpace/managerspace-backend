<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'id_company',
        'amount',
        'date',
        'description',
        'is_recurring',
        'tax_deductible',
        'recurrence_frequency',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'is_recurring' => 'boolean',
        'tax_deductible' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
