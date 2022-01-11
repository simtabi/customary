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
        'sub_group',
        'key',
        'value',
        'description',
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
