<?php

namespace Simtabi\Customary\Models;

use Illuminate\Database\Eloquent\Model;
use Simtabi\Laranail\Supports\Model\Traits\UUID\WithUuid;

class Customary extends Model
{
    use WithUuid;

    protected $guarded  = ['updated_at', 'id'];

    protected $fillable = [
        'id',
        'group',
        'sub_group',
        'key',
        'value',
        'description',
        'order',
        'ownerable_type',
        'ownerable_id',
        'locale',
    ];

    protected $table    = 'customary_options';

    public function scopeGroup($query, $groupName)
    {
        return $query->whereGroup($groupName);
    }

    public function scopeKeyValueByOwnersId($query, $key, $ownersId, $group)
    {
        $query = $query->where('ownerable_id', $ownersId)->where('group', $group)->where('key', $key)->get()->first();

        return $query->value ?? false;
    }

    /**
     * Get all owning ownerable models.
     */
    public function ownerable()
    {
        return $this->morphTo();
    }
}
