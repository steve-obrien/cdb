<?php

namespace App\Models\Traits;

use App\Models\ModelVersion;

trait Versionable
{
    public static function bootVersionable()
    {
        static::updating(function ($model) {
            $model->storeVersion();
        });
    }

    public function storeVersion()
    {
        ModelVersion::create([
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'data' => $this->getOriginal(),
        ]);
    }

    public function versions()
    {
        return $this->morphMany(ModelVersion::class, 'model');
    }

    public function getOriginalVersion()
    {
        return $this->versions()->oldest()->first();
    }

    public function getPreviousVersion()
    {
        return $this->versions()->latest()->first();
    }

    public function getVersionHistory()
    {
        return $this->versions()->orderBy('created_at', 'asc')->get();
    }
}
