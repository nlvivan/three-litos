<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $searchable = [
        'name',
    ];

    protected $appends = [
        'image',
    ];

    public function image(): Attribute
    {
        /** @var Storage */
        $storage = Storage::disk('public');

        return Attribute::make(
            get: function () use ($storage) {
                return [
                    'image_url' => $this->image_url ? $storage->url($this->image_url) : null,
                ];
            }
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
