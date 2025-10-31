<?php

namespace App\Models\Scopes;

use App\Enums\RoleEnum;
use App\Models\Office;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OfficesOfficeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // No authenticated user → deny all
        if (! $user) {
            $builder->whereRaw('1 = 0');
            return;
        }

        // Full access for Super Admins and Admins
        if ($user->hasRole([RoleEnum::SUPER_ADMIN->value, RoleEnum::ADMIN->value])) {
            return;
        }

        // Map roles to office IDs
        $roleToOffice = [
            RoleEnum::EDARA->value             => Office::EDARA,
            RoleEnum::OFFICER_AFFAIRES->value  => Office::OFFICER_AFFAIRES,
            RoleEnum::SEGELAT->value           => Office::SEGELAT,
            RoleEnum::PERSONAL_AFFAIRES->value => Office::PERSONAL_AFFAIRES,
        ];

        // Get all offices corresponding to user roles
        $officeIds = collect($roleToOffice)
            ->filter(fn ($officeId, $role) => $user->hasRole($role))
            ->values()
            ->all();

        // No matching roles → deny access
        if (empty($officeIds)) {
            $builder->whereRaw('1 = 0');
            return;
        }

        // Apply filtering: only categories belonging to user's offices
        $builder->whereIn('offices.id', $officeIds);
    }
}
