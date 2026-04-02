<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'client_id', 'order_number', 'type', 'status',
        'payment_status', 'delivery_status', 'order_date', 'delivery_date',
        'estimated_delivery_date', 'subtotal', 'tax_amount', 'discount_amount',
        'shipping_cost', 'total', 'shipping_address_line1', 'shipping_address_line2',
        'shipping_city', 'shipping_state', 'shipping_postal_code', 'shipping_country',
        'shipping_phone', 'billing_address_line1', 'billing_address_line2',
        'billing_city', 'billing_state', 'billing_postal_code', 'billing_country',
        'notes', 'internal_notes', 'tracking_number', 'carrier', 'shipped_at',
        'delivered_at', 'invoice_id', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'estimated_delivery_date' => 'date',
        'shipped_at' => 'date',
        'delivered_at' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->order_number)) {
                $model->order_number = 'CMD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
            if (empty($model->order_date)) {
                $model->order_date = now();
            }
            $model->created_by = $model->created_by ?? auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    // Methods
    public function canBeCancelled(): bool
    {
        return !in_array($this->status, ['delivered', 'cancelled', 'refunded']);
    }

    public function canBeModified(): bool
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    public function addHistory($statusTo, $notes = null, $statusFrom = null)
    {
        return $this->histories()->create([
            'status_from' => $statusFrom ?? $this->getOriginal('status'),
            'status_to' => $statusTo,
            'notes' => $notes,
            'user_id' => auth()->id(),
        ]);
    }

    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        $this->addHistory($newStatus, $notes, $oldStatus);

        return true;
    }

    public function calculateTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $taxAmount = $this->items->sum(function ($item) {
            return ($item->quantity * $item->unit_price) * ($item->tax_rate / 100);
        });

        $discountAmount = $this->items->sum('discount_amount');

        $this->subtotal = $subtotal;
        $this->tax_amount = $taxAmount;
        $this->discount_amount = $discountAmount;
        $this->total = $subtotal + $taxAmount + $this->shipping_cost - $discountAmount;

        return $this;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Brouillon',
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'processing' => 'En traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            'refunded' => 'Remboursée',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'processing' => 'orange',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'refunded' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'En attente',
            'partial' => 'Partiel',
            'paid' => 'Payé',
            'refunded' => 'Remboursé',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }
}
