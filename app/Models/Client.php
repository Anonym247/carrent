<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $appends = ['car'];

    /**
     * @var string[]
     */
    protected $hidden = ['created_at', 'updated_at', 'cars'];

    /**
     * @return BelongsToMany
     */
    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_client');
    }

    /**
     * @return array|null
     */
    public function getCarAttribute(): ?array
    {
        $car = $this->cars->first();

        if (!$car) {
            return null;
        }

        return [
            'car_id' => $car->id,
            'car_name' => $car->name,
        ];
    }
}
