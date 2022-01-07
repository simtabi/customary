<?php

namespace Simtabi\Customary\Models;

use Illuminate\Database\Eloquent\Model;
use Simtabi\Laranail\Traits\HasUuid;

class Customary extends Model
{
    use HasUuid;

    protected $guarded  = ['updated_at', 'id'];

    protected $fillable = [
        'id',
        'ownerable_type',
        'ownerable_id',
        'group',
        'key',
        'value',
        'locale',
    ];

    protected $table    = 'customary_options';

    public function scopeGroup($query, $groupName)
    {
        return $query->whereGroup($groupName);
    }
    
    public function scopeGetKeyValueByOwnersId($query, $key, $ownersId, $group)
    {
        $query = $query->where('ownerable_id', $ownersId)->where('group', $group)->where('key', $key)->get()->first();

        return $query->value;
    }

    /**
     * Get all owning ownerable models.
     */
    public function ownerable()
    {
        return $this->morphTo();
    }
}
