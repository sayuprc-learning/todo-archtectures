<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * 属性のキャスト設定
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'completed' => 'bool',
            'due_date' => 'immutable_date',
        ];
    }
}
