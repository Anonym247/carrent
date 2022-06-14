<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Car extends Model
{
    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $appends = ['client'];

    /**
     * @var string[]
     */
    protected $hidden = ['created_at', 'updated_at', 'clients'];

    /**
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'car_client');
    }

    /**
     * @return array|null
     */
    public function getClientAttribute(): ?array
    {
        $client = $this->clients->first();

        if (!$client) {
            return null;
        }

        return [
            'id' => $client->id,
            'name' => $client->name,
        ];
    }
}
