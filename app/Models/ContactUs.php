<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = [
        'company_id',
        'department','subject','name','email','message',
        'attachment_path','ip','user_agent'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
