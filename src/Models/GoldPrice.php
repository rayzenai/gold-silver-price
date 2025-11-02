<?php

namespace RayzenAI\GoldSilverPrice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RayzenAI\GoldSilverPrice\Database\Factories\GoldPriceFactory;

class GoldPrice extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): GoldPriceFactory
    {
        return GoldPriceFactory::new();
    }

    protected $fillable = [
        'date',
        'gold_per_tola',
        'gold_per_10g',
        'silver_per_tola',
        'silver_per_10g',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('gold-silver-price.table_name', 'gold_prices');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'gold_per_tola' => 'integer',
            'gold_per_10g' => 'integer',
            'silver_per_tola' => 'integer',
            'silver_per_10g' => 'integer',
        ];
    }

    /**
     * Get the latest gold/silver prices
     */
    public static function latest(): ?self
    {
        return self::orderBy('date', 'desc')->first();
    }

    /**
     * Get or create today's price record
     */
    public static function today(): self
    {
        return self::firstOrCreate(
            ['date' => today()],
            [
                'gold_per_tola' => 0,
                'gold_per_10g' => 0,
                'silver_per_tola' => 0,
                'silver_per_10g' => 0,
            ]
        );
    }
}
