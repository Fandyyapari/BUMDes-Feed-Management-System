<?php

namespace App\Filament\Resources\FeedIssues\Pages;

use App\Filament\Resources\FeedIssues\FeedIssueResource;
use App\Services\FeedIssueService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateFeedIssue extends CreateRecord
{
    protected static string $resource = FeedIssueResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(FeedIssueService::class)->create($data);
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
        return 'Pakan keluar berhasil dicatat';
    }
}