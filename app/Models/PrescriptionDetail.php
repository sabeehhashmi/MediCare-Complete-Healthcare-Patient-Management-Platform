<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrescriptionDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'direction_id',
        'frquency_id',
        'duration_id',
        'quantity',
        'instructions',
        'dosage_id',
        'dosage_value',
        'duration_value',
        'created_by',
        'last_updated_by',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicin::class, 'medicine_id');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

    public function frequency()
    {
        return $this->belongsTo(Frequency::class, 'frquency_id');
    }

    public function dosage()
    {
        return $this->belongsTo(Dosage::class);
    }

    public function duration()
    {
        return $this->belongsTo(Duration::class);
    }
}

