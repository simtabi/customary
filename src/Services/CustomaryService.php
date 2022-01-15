<?php

namespace Simtabi\Customary\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Simtabi\Customary\Contracts\CustomaryInterface as Contract;
use Simtabi\Customary\Models\Customary;

class CustomaryService implements Contract
{
    /**
     * Group name.
     *
     * @var string
     */
    protected $groupName = 'default';

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey  = 'customary_options';

    /**
     * {@inheritdoc}
     */
    public function all($fresh = false)
    {
        if ($fresh) {
            return $this->modelQuery()->pluck('value', 'key');
        }

        return Cache::rememberForever($this->getCacheKey(), function () {
            return $this->modelQuery()->pluck('value', 'key');
        });
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null)
    {
        // if it's an array, batch save customary
        if (is_array($key)) {
            foreach ($key as $name => $item) {
                $this->set($name, $item);
            }
            return true;
        }

        $setting = $this->getCustomaryModel()->firstOrNew([
            'group' => $this->groupName,
            'key'   => $key,
        ]);

        $setting->value = $value;
        $setting->group = $this->groupName;
        $setting->save();

        $this->flushCache();

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $fresh = false)
    {
        return $this->all($fresh)->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->all()->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $deleted = $this->getCustomaryModel()->where('key', $key)->delete();

        $this->flushCache();

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function flushCache()
    {
        return Cache::forget($this->getCacheKey());
    }

    /**
     * Get customary cache key.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cacheKey.'.'.$this->groupName;
    }

    /**
     * Get customary eloquent model.
     *
     * @return Builder
     */
    protected function getCustomaryModel()
    {
        return app(Customary::class);
    }

    /**
     * Get the model query builder.
     *
     * @return Builder
     */
    protected function modelQuery()
    {
        return $this->getCustomaryModel()->group($this->groupName);
    }

    /**
     * Set the group name for customary.
     *
     * @param  string  $groupName
     * @return $this
     */
    public function group($groupName)
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * Fetch a key's value based on the given group and owners id
     *
     * @param $key
     * @param $ownersId
     * @param $group
     * @return mixed
     */
    public function keyValueByOwnersId($key, $ownersId, $group)
    {
        return $this->getCustomaryModel()->KeyValueByOwnersId($key, $ownersId, $group);
    }

    public function createThroughModel(Model $model, array $data, string $group, string $method): bool
    {
        $status = false;
        $passed = [];

        if (method_exists($model, $method))
        {
            $model->refresh();
            foreach ($data as $key => $datum)
            {
                // create only if unique
                $unique = $model->$method()->where('key', $key)->where('group', $group)->get();
                if ($unique->isEmpty()) {
                    $query = new Customary([
                        'group'       => $group,
                        'sub_group'   => $datum['sub_group']   ?? null,
                        'key'         => $key,
                        'value'       => $datum['value']       ?? null,
                        'order'       => (int) $datum['order'] ?? 0,
                        'description' => $datum['description'] ?? null,
                    ]);

                    if ($model->$method()->save($query))
                    {
                        $passed[] = $key;
                        $status   = true;
                    }
                }
            }
        }

        return $status && (count($passed) == count($data));
    }

    public function updateThroughModel(Model $model, array $data, string $group, string $method): bool
    {

        $status = false;
        $passed = [];

        if (method_exists($model, $method))
        {
            $model->refresh();
            foreach ($data as $key => $datum)
            {
                $subGroup       = $datum['sub_group']   ?? null;
                $description    = $datum['description'] ?? null;
                $ready['value'] = $datum['value']       ?? null;
                $ready['order'] = (int) $datum['order'] ?? 0;
                if (!empty($subGroup)) {
                    $ready['sub_group'] = $subGroup;
                }
                if (!empty($description)) {
                    $ready['description'] = $description;
                }

                $query = $model->$method()->where('key', $key)->where('group', $group)->update(array_merge([
                    'group'       => $group,
                    'key'         => $key,
                ], $ready));

                if ($query)
                {
                    $passed[] = $key;
                    $status   = true;
                }
            }
        }

        return $status && (count($passed) == count($data));
    }
}
