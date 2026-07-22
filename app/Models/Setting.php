<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function setValue(string $key, mixed $value, ?string $description = null): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description],
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }
}
