<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;

#[Fillable([
    'username',
    'password',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    #[Override]
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function hotlines()
    {
        return $this->hasMany(Hotline::class);
    }
}
