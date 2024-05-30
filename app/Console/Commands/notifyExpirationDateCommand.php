<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Filament\Notifications\Actions\Action;

class notifyExpirationDateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-expiration-date-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::role(['Staff', 'Admin'])->get();

        Product::query()
            ->each(function (Product $product) use ($users) {

                if($product->expiry_date <= now()->addWeek()) {

                    \Filament\Notifications\Notification::make()
                    ->title("{$product->name} will expire on {$product->expiry_date}")
                    ->warning()
                    ->icon('heroicon-o-exclamation-circle')
                    ->actions([
                        Action::make('markAsUnread')
                            ->button()
                            ->markAsUnread(),
                    ])
                    ->sendToDatabase($users);
                }
            });
    }
}
