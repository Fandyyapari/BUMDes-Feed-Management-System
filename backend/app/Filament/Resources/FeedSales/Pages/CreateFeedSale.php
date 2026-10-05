<?php

namespace App\Filament\Resources\FeedSales\Pages;

use App\Filament\Resources\FeedSales\FeedSaleResource;
use App\Models\User;
use App\Services\FeedSaleService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateFeedSale extends CreateRecord
{
    protected static string $resource = FeedSaleResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        $actor = Filament::auth()->user();

        abort_unless($actor instanceof User, 403);

        try {
            return app(FeedSaleService::class)->create($data, $actor);
        } catch (ValidationException $exception) {
            $errors = [];

            foreach ($exception->errors() as $field => $messages) {
                $errors["data.{$field}"] = $messages;
            }

            throw ValidationException::withMessages($errors);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Penjualan pakan berhasil dicatat';
    }
}