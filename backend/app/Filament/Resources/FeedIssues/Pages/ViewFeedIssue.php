<?php

namespace App\Filament\Resources\FeedIssues\Pages;

use App\Filament\Resources\FeedIssues\FeedIssueResource;
use App\Models\User;
use App\Services\FeedCancellationService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class ViewFeedIssue extends ViewRecord
{
    protected static string $resource = FeedIssueResource::class;

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
                ->modalHeading('Batalkan Pakan Keluar')
                ->modalDescription(
                    'Pembatalan mengembalikan jumlah pakan keluar ke stok. '
                    . 'Gunakan untuk memperbaiki salah pencatatan. '
                    . 'Transaksi tetap tersimpan sebagai riwayat.'
                )
                ->modalSubmitActionLabel('Ya, Batalkan Transaksi')
                ->modalCancelActionLabel('Kembali')
                ->schema([
                    Textarea::make('cancellation_reason')
                        ->label('Alasan Pembatalan')
                        ->placeholder('Contoh: Salah mencatat jumlah pakan keluar.')
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
                        ->body('Jumlah pakan telah dikembalikan ke stok.')
                        ->success()
                        ->send();

                    $this->redirect(
                        FeedIssueResource::getUrl('index')
                    );
                }),
        ];
    }
}