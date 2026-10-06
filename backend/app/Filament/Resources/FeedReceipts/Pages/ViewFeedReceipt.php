<?php

namespace App\Filament\Resources\FeedReceipts\Pages;

use App\Filament\Resources\FeedReceipts\FeedReceiptResource;
use App\Models\User;
use App\Services\FeedCancellationService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class ViewFeedReceipt extends ViewRecord
{
    protected static string $resource = FeedReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelTransaction')
                ->label('Batalkan Transaksi')
                ->color('danger')
                ->visible(function (): bool {
                    $user = Filament::auth()->user();

                    return $user instanceof User
                        && $user->can_cancel_stock
                        && ! $this->getRecord()->isCancelled();
                })
                ->modalHeading('Batalkan Pakan Masuk')
                ->modalDescription(
                    'Pembatalan mengurangi stok sesuai jumlah pakan masuk. '
                    . 'Transaksi tetap tersimpan sebagai riwayat.'
                )
                ->modalSubmitActionLabel('Ya, Batalkan Transaksi')
                ->modalCancelActionLabel('Kembali')
                ->schema([
                    Textarea::make('cancellation_reason')
                        ->label('Alasan Pembatalan')
                        ->placeholder('Contoh: Salah memasukkan jumlah pakan masuk.')
                        ->required()
                        ->minLength(5)
                        ->maxLength(1000)
                        ->rows(3),
                ])
                ->action(function (array $data, Action $action): void {
                    $user = Filament::auth()->user();

                    abort_unless($user instanceof User, 403);

                    try {
                        app(FeedCancellationService::class)->cancel(
                            $this->getRecord(),
                            $data['cancellation_reason'],
                            $user
                        );
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title('Pembatalan ditolak')
                            ->body(
                                collect($exception->errors())
                                    ->flatten()
                                    ->first()
                            )
                            ->danger()
                            ->send();

                        $action->halt();
                    } catch (AuthorizationException $exception) {
                        Notification::make()
                            ->title('Tidak memiliki izin')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        $action->halt();
                    }

                    Notification::make()
                        ->title('Transaksi berhasil dibatalkan')
                        ->body('Stok sudah dihitung ulang.')
                        ->success()
                        ->send();

                    $this->redirect(
                        FeedReceiptResource::getUrl('index')
                    );
                }),
        ];
    }
}