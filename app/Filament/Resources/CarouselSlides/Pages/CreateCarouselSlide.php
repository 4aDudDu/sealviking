<?php

namespace App\Filament\Resources\CarouselSlides\Pages;

use App\Filament\Resources\CarouselSlides\CarouselSlideResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCarouselSlide extends CreateRecord
{
    protected static string $resource = CarouselSlideResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
