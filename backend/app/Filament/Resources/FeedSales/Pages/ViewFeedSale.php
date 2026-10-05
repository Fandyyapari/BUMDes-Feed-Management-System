<?php

namespace App\Filament\Resources\FeedSales\Pages;

use App\Filament\Resources\FeedSales\FeedSaleResource;
use App\Models\FeedSale;
use App\Models\User;
use App\Services\FeedCancellationService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class ViewFeedSale extends ViewRecord
{
    protected static string $resource = FeedSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelSale')
                ->label('Batalkan Penjualan')
                ->color('danger')
                ->visible(function (): bool {
                    $user = Filament::auth()->user();

                    return $user instanceof User
                        && (bool) $user->can_cancel_stock
                        && ! $this->getRecord()->isCancelled();
                })
                ->requiresConfirmation()
                ->modalHeading('Batalkan pencatatan penjualan?')
                ->modalDescription(
                    'Gunakan untuk mengoreksi salah pencatatan. '
                    . 'Jumlah kemasan akan kembali ke stok dan '
                    . 'riwayat penjualan tetap tersimpan.'
                )
                ->modalSubmitActionLabel('Ya, batalkan penjualan')
                ->modalCancelActionLabel('Kembali')
                ->schema([
                    Textarea::make('cancellation_reason')
                        ->label('Alasan Pembatalan')
                        ->placeholder(
                            'Contoh: Salah memasukkan jumlah kemasan.'
                        )
                        ->required()
                        ->minLength(5)
                        ->maxLength(1000)
                        ->rows(3),
                ])
                ->action(function (array $data, Action $action): void {
                    $user = Filament::auth()->user();
                    $record = $this->getRecord();

                    abort_unless(
                        $user instanceof User
                            && $record instanceof FeedSale,
                        403
                    );

                    try {
                        app(FeedCancellationService::class)->cancel(
                            $record,
                            $data['cancellation_reason'],
                            $user
                        );
                    } catch (ValidationException $exception) {
                        $messages = [];

                        foreach ($exception->errors() as $errors) {
                            foreach ($errors as $message) {
                                $messages[] = $message;
                            }
                        }

                        Notification::make()
                            ->title('Pembatalan ditolak')
                            ->body(implode(' ', $messages))
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    } catch (AuthorizationException $exception) {
                        Notification::make()
                            ->title('Tidak memiliki izin')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    }

                    Notification::make()
                        ->title('Penjualan berhasil dibatalkan')
                        ->body('Stok sudah dihitung kembali.')
                        ->success()
                        ->send();

                    $this->redirect(
                        FeedSaleResource::getUrl('index')
                    );
                }),
        ];
    }
}