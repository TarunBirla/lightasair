<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'vendor_id',
        'type',
        'subtotal',
        'commission_rate',
        'commission_amount',
        'vendor_payout',
        'payment_status',
        'fulfillment_status',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_payout' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payout()
    {
        return $this->hasOne(Payout::class);
    }

    public static function generateOrderNumber()
    {
        return 'LAA-' . strtoupper(Str::random(8));
    }

    public function calculateCommission($categoryId, $listingType)
    {
        $rate = Commission::getRate($categoryId, $listingType);
        $this->commission_rate = $rate;
        $this->commission_amount = $this->subtotal * ($rate / 100);
        $this->vendor_payout = $this->subtotal - $this->commission_amount;
    }

    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isDelivered()
    {
        return $this->fulfillment_status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->fulfillment_status === 'cancelled';
    }
}
