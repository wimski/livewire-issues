<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string          $id
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
abstract class AbstractModel extends Model
{
    protected $keyType    = 'string';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function getKey(): string
    {
        /** @var string $key */
        $key = parent::getKey();

        return $key;
    }
}
