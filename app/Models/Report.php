<?php

namespace App\Models;
//use App\Models\reportItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    //report created sihon save endihonu allow mnargachiew negroch nachiew or inseted
    protected $fillable = [
        'user_id',
        'report_date',
        'report_type',
        'status',        
        'feedback',
        'seen',
    
        //wanna use creat() it must be in fillble

    ];
    protected $casts = [
    'report_date' => 'date',
];

    public function user()
    {
        return $this->belongsTo(User::class); // 1 to 1
    }

    public function items()
   {
    return $this->hasMany(ReportItem::class);//rowa r bzu itms
   }
}
