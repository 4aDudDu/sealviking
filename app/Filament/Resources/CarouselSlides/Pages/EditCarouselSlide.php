<?php

namespace App\Filament\Resources\CarouselSlides\Pages;

use App\Filament\Resources\CarouselSlides\CarouselSlideResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarouselSlide extends EditRecord
{
    protected static string $resource = CarouselSlideResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
