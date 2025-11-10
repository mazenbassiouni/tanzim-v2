<?php

namespace App\Models\Scopes;

use App\Enums\RoleEnum;
use App\Models\Category;
use App\Models\Office;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class MissionsOfficeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->runningInConsole()) {
            return;
        }

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

        // Map roles to corresponding office IDs
        $roleToOffice = [
            RoleEnum::EDARA->value             => Office::EDARA,
            RoleEnum::OFFICER_AFFAIRES->value  => Office::OFFICER_AFFAIRES,
            RoleEnum::SEGELAT->value           => Office::SEGELAT,
            RoleEnum::PERSONAL_AFFAIRES->value => Office::PERSONAL_AFFAIRES,
        ];

        // Collect all office IDs for roles the user actually has
        $officeIds = collect($roleToOffice)
            ->filter(fn ($officeId, $role) => $user->hasRole($role))
            ->values()
            ->all();

        // No matching roles → deny all
        if (empty($officeIds)) {
            $builder->whereRaw('1 = 0');
            return;
        }

        // Apply mission office visibility rules
        $builder->where(function (Builder $query) use ($officeIds) {
            // missions whose category.office is in user's offices
            $query->whereHas('category.office', function (Builder $q) use ($officeIds) {
                $q->whereIn('offices.id', $officeIds);
            })
            // OR missions with category = GENERAL and office in user's offices
            ->orWhere(function (Builder $sub) use ($officeIds) {
                $sub->where('category_id', Category::GENERAL)
                    ->whereHas('office', function (Builder $q) use ($officeIds) {
                        $q->whereIn('offices.id', $officeIds);
                    });
            });
        });

    }
}
