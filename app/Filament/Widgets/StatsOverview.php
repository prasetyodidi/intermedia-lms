<?php

namespace App\Filament\Widgets;

use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function getDinamicDivision()
    {
        return Division::all()->pluck('name', 'id');
    }
    protected function getStats(): array
    {
        $divisionStats = [];
        foreach ($this->getDinamicDivision() as $key => $value) {
            $divisionStats[] = Stat::make($value, User::where('division_id', $key)->count())
                ->color('blue')
                ->icon('heroicon-o-user-group')
                ->description('Total anggota divisi ' . $value)
                ->descriptionColor('gray');
        }

        return array_merge([
            Stat::make('Total User', User::count())
                ->icon('heroicon-o-user')
                ->description('Total user yang terdaftar'),
            Stat::make('Total Divisi', Division::count())
                ->icon('heroicon-o-briefcase')
        ], $divisionStats, [
            Stat::make('Total Roles', Role::count())
                ->color('green')
                ->icon('heroicon-o-shield-check'),
        ]);
    }

    public static function canView(): bool
    {
        return User::find(auth()->user()->id)->hasRole('Super Admin');
    }
}
