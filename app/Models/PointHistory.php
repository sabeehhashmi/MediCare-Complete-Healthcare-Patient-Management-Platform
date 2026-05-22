<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'type',
        'points',
        'appointment_id'
    ];

    protected $translatable = ['description']; 

    public function getAttributes()
    {
        foreach($this->translatable as $attribute) {
            $locale = app()->getLocale();
            $localizedKey = "{$attribute}_{$locale}";
            if (isset($this->attributes[$localizedKey])) {
                $this->attributes[$attribute] =  $this->attributes[$localizedKey];
            }
        }
        return $this->attributes;
    }

    public function appointment()
{
    return $this->belongsTo(DoctorPatientAppointment::class, 'appointment_id', 'id');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
