<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->using(function (array $data, string $model): Model {

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt('password'),
                ]);

                $user->assignRole('Customer');

                return $user->customer()->create([
                    'name' => $data['name'],
                    'address' => $data['address'],
                ]);
            }),
        ];
    }
}
