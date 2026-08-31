<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'custom_link',
        'is_link_active',
        'bio',
        'avatar',
        'google_id',
        'role',
        'theme',
        'theme_color',
        'suspended_at',
        'suspended_until',
        'suspend_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_link_active'    => 'boolean',
            'suspended_at'      => 'datetime',
            'suspended_until'   => 'datetime',
        ];
    }

    /** Cek apakah akun sedang di-suspend */
    public function isSuspended(): bool
    {
        if (is_null($this->suspended_at)) {
            return false;
        }

        // Jika memiliki batas waktu dan waktu sekarang sudah melewati batas, masa suspend selesai
        if ($this->suspended_until && now()->greaterThan($this->suspended_until)) {
            return false;
        }

        return true;
    }

    public function shortlinks(): HasMany
    {
        return $this->hasMany(Shortlink::class);
    }

    public function shortlinkClicks(): HasMany
    {
        return $this->hasMany(ShortlinkClick::class);
    }

    public function digitalProducts(): HasMany
    {
        return $this->hasMany(DigitalProduct::class);
    }

    public function suspensionAppeals(): HasMany
    {
        return $this->hasMany(SuspensionAppeal::class);
    }
}
