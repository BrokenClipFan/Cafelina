<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Setting;
use App\Models\Purchase;
use App\Models\EmployeeSchedule;

#[Fillable(['name', 'email', 'password', 'role', 'status', 'online_status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_CASHIER = 'cashier';
    public const ROLE_STOCK_CLERK = 'stock_clerk';

    public const ROLES = [
        self::ROLE_CASHIER => 'Cashier',
        self::ROLE_STOCK_CLERK => 'Stock Clerk',
        self::ROLE_ADMIN => 'Admin',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $adminEmail = Setting::where('name', 'email')->firstOrFail();

            if ($user->email === $adminEmail->value) {
                $user->role = self::ROLE_ADMIN;
                $user->status = self::STATUS_APPROVED;
            } 
        });
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst((string) $this->role);
    }

    /**
     * Where each role lands after logging in.
     */
    public function homeRoute(): string
    {
        if (! $this->isApproved()) {
            return 'account.pending';
        }

        return match ($this->role) {
            self::ROLE_ADMIN => 'admin.dashboard',
            self::ROLE_STOCK_CLERK => 'admin.inventory',
            default => 'home',
        };
    }

    public function orders() {
        return $this->hasMany(Purchase::class, 'user_id');
    }

    public function schedules() {
        return $this->hasMany(EmployeeSchedule::class, 'user_id');
    }

}
