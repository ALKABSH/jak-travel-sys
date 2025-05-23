<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class Request extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requests';

    protected $fillable = [
        'service_id', 
        'user_id',     // Add user_id to fillable fields
        'customer_id', 
        'agency_id', 
        'title',      // Adding fields used in tests
        'description',
        'details', 
        'status',
        'required_date',
        'notes',
        'priority', 
        'requested_date'
    ];

    protected $casts = [
        'required_date' => 'date',
        'requested_date' => 'date',
    ];

    /**
     * Set the required date attribute (store only date)
     */
    public function setRequiredDateAttribute($value)
    {
        $this->attributes['required_date'] = optional(
            Carbon::parse($value)
        )->toDateString();
    }

    /**
     * Set the requested date attribute (store only date)
     */
    public function setRequestedDateAttribute($value)
    {
        $this->attributes['requested_date'] = optional(
            Carbon::parse($value)
        )->toDateString();
    }

    /**
     * Get the user (customer) that owns the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Check if the request is pending
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if the request is approved
     * 
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    
    /**
     * Check if the request is completed
     * 
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    
    /**
     * Check if the request is rejected
     * 
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    
    /**
     * Check if the request is canceled
     * 
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }
}
