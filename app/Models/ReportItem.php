<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportItem extends Model
{
    use HasFactory;
     protected $fillable = [

       
        'business_name',
        'location',
        'specific_location',
        'contact_name',
        'phone',
        'contact_method',
        'status',
        'interaction_result',
        'interest_level',
        'commitment',
        'next_action',
        'next_follow_up_date',
        'remark',

     ];
   //this belongs to one report
     public function report()
    {
        return $this->belongsTo(Report::class);
    }
    public function scopeMine($query)
{
    return $query->whereHas('report', function ($q) {
        $q->where('user_id', auth()->id());
    });
}
}
