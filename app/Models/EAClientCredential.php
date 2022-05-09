<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $client_id
 * @property string $client_secret
 * @property string $vendor_code
 */
class EAClientCredential extends Model
{
    use HasFactory;
    protected $table = 'ea_client_credentials';

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class, 'ea_client_credential_id');
    }
}
