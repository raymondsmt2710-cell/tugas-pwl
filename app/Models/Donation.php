<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id', 'user_id', 'donation_amount', 
        'donor_name', 'donor_email', 'donor_message',
        'payment_method', 'payment_status', 'transaction_id'
    ];
    
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class)->nullable();
    }
}

