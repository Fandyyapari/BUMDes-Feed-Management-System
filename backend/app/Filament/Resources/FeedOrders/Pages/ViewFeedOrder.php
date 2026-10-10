<?php

namespace App\Filament\Resources\FeedOrders\Pages;

use App\Filament\Resources\FeedOrders\FeedOrderResource;
use App\Models\FeedOrder;
use App\Models\User;
use App\Services\FeedOrderProcessingService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\Select;

class ViewFeedOrder extends ViewRecord
{
    protected static string $resource = FeedOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirmOrder')
                ->label('Konfirmasi Pesanan')
                ->color('success')
                ->authorize(
                    fn(): bool => FeedOrderResource::canViewAny()
                )
                ->visible(
                    fn(): bool =>
                    $this->getRecord()->status
                        === FeedOrder::STATUS_WAITING
                )
                ->modalHeading('Konfirmasi Pesanan')
                ->modalDescription(
                    'Isi informasi pengambilan dan pembayaran. '
                        . 'Konfirmasi belum mencadangkan stok.'
                )
                ->modalSubmitActionLabel('Konfirmasi')
                ->modalCancelActionLabel('Kembali')
                ->schema([
                    Textarea::make('admin_notes')
                        ->label('Informasi untuk Pelanggan')
                        ->placeholder(
                            'Contoh: Silakan ambil di BUMDes '
                                . 'pukul 09.00. Pembayaran saat pengambilan.'
                        )
                        ->helperText(
                            'Informasi ini akan terlihat oleh pelanggan.'
                        )
                        ->required()
                        ->minLength(5)
                        ->maxLength(1000)
                        ->rows(4),
                ])
                ->action(function (array $data, Action $action): void {
                    $actor = Auth::user();

                    abort_unless($actor instanceof User, 403);

                    try {
                        app(FeedOrderProcessingService::class)->confirm(
                            $this->getRecord(),
                            $data['admin_notes'],
                            $actor
                        );
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title('Konfirmasi belum berhasil')
                            ->body(
                                collect($exception->errors())
                                    ->flatten()
                                    ->implode(' ')
                            )
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    }

                    Notification::make()
                        ->title('Pesanan berhasil dikonfirmasi')
                        ->success()
                        ->send();

                    $this->redirect(
                        FeedOrderResource::getUrl('view', [
                            'record' => $this->getRecord()->getKey(),
                        ])
                    );
                }),
            Action::make('rejectOrder')
                ->label('Tolak Pesanan')
                ->color('danger')
                ->authorize(
                    fn(): bool => FeedOrderResource::canViewAny()
                )
                ->visible(
                    fn(): bool =>
                    $this->getRecord()->status
                        === FeedOrder::STATUS_WAITING
                )
                ->modalHeading('Tolak Pesanan')
                ->modalDescription(
                    'Tuliskan alasan penolakan agar pelanggan '
                        . 'mengetahui mengapa pesanannya tidak dapat dipenuhi.'
                )
                ->modalSubmitActionLabel('Tolak Pesanan')
                ->modalCancelActionLabel('Kembali')
                ->schema([
                    Textarea::make('admin_notes')
                        ->label('Alasan Penolakan')
                        ->placeholder(
                            'Contoh: Stok belum mencukupi. '
                                . 'Silakan pesan kembali setelah stok tersedia.'
                        )
                        ->helperText(
                            'Alasan ini akan terlihat oleh pelanggan.'
                        )
                        ->required()
                        ->minLength(5)
                        ->maxLength(1000)
                        ->rows(4),
                ])
                ->action(function (array $data, Action $action): void {
                    $actor = Auth::user();

                    abort_unless($actor instanceof User, 403);

                    try {
                        app(FeedOrderProcessingService::class)->reject(
                            $this->getRecord(),
                            $data['admin_notes'],
                            $actor
                        );
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title('Penolakan belum berhasil')
                            ->body(
                                collect($exception->errors())
                                    ->flatten()
                                    ->implode(' ')
                            )
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    }

                    Notification::make()
                        ->title('Pesanan telah ditolak')
                        ->success()
                        ->send();

                    $this->redirect(
                        FeedOrderResource::getUrl('view', [
                            'record' => $this->getRecord()->getKey(),
                        ])
                    );
                }),
            Action::make('completeOrder')
                ->label('Selesaikan Pesanan')
                ->color('success')
                ->authorize(
                    fn(): bool => FeedOrderResource::canViewAny()
                )
                ->visible(
                    fn(): bool =>
                    $this->getRecord()->status
                        === FeedOrder::STATUS_CONFIRMED
                        && $this->getRecord()->feed_sale_id === null
                )
                ->modalHeading('Selesaikan Pesanan')
                ->modalDescription(
                    'Lanjutkan setelah pakan diserahkan '
                        . 'dan pembayaran diterima. '
                        . 'Sistem akan mencatat penjualan dan mengurangi stok.'
                )
                ->modalSubmitActionLabel('Selesaikan dan Catat Penjualan')
                ->modalCancelActionLabel('Kembali')
                ->schema([
                    Select::make('payment_method')
                        ->label('Metode Pembayaran yang Diterima')
                        ->options([
                            'tunai' => 'Tunai',
                            'transfer' => 'Transfer',
                        ])
                        ->placeholder('Pilih metode pembayaran')
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data, Action $action): void {
                    $actor = Auth::user();

                    abort_unless($actor instanceof User, 403);

                    try {
                        app(FeedOrderProcessingService::class)->complete(
                            $this->getRecord(),
                            $data['payment_method'],
                            $actor
                        );
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title('Pesanan belum dapat diselesaikan')
                            ->body(
                                collect($exception->errors())
                                    ->flatten()
                                    ->implode(' ')
                            )
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    }

                    Notification::make()
                        ->title('Pesanan selesai')
                        ->body('Penjualan berhasil dicatat.')
                        ->success()
                        ->send();

                    $this->redirect(
                        FeedOrderResource::getUrl('view', [
                            'record' => $this->getRecord()->getKey(),
                        ])
                    );
                }),
        ];
    }
}
