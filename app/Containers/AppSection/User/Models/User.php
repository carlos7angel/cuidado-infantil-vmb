<?php

namespace App\Containers\AppSection\User\Models;

use App\Containers\AppSection\Authorization\Enums\Role as RoleEnum;
use App\Containers\AppSection\User\Data\Collections\UserCollection;
use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Models\UserModel as ParentUserModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class User extends ParentUserModel
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_client',
        'login_attempt',
        'last_login_at',
        'active',
        'childcare_center_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'immutable_datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_client' => 'boolean',
        'active' => 'boolean',
    ];

    public function newCollection(array $models = []): UserCollection
    {
        return new UserCollection($models);
    }

    /**
     * Allows Passport to find the user by email (case-insensitive).
     */
    public function findForPassport(string $username): self|null
    {
        return self::orWhereRaw('lower(email) = lower(?)', [$username])->first();
    }

    public function isSuperAdmin(): bool
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (!$this->hasRole(RoleEnum::SUPER_ADMIN, $guard)) {
                return false;
            }
        }

        return true;
    }

    public function hasAdminWebRole(): bool
    {
        return $this->hasRole([RoleEnum::SUPER_ADMIN, RoleEnum::MUNICIPAL_ADMIN, RoleEnum::CHILDCARE_ADMIN]);
    }

    protected function email(): Attribute
    {
        return new Attribute(
            get: static fn (string|null $value): string|null => is_null($value) ? null : strtolower($value),
        );
    }

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the educator profile for this user (if any).
     */
    public function educator(): HasOne
    {
        return $this->hasOne(Educator::class);
    }

    /**
     * Get the childcare center assigned to this user.
     */
    public function childcareCenter(): BelongsTo
    {
        return $this->belongsTo(ChildcareCenter::class);
    }

    // ==========================================================================
    // Helpers
    // ==========================================================================

    /**
     * Check if user has an educator profile.
     */
    public function isEducator(): bool
    {
        return $this->educator()->exists();
    }

    /**
     * Get the childcare centers where this user is assigned as educator.
     */
    public function getAssignedChildcareCenters()
    {
        return $this->educator?->childcareCenters ?? collect();
    }
}
