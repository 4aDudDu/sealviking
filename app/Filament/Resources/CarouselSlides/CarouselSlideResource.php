<?php

namespace App\Filament\Resources\CarouselSlides;

use App\Filament\Resources\CarouselSlides\Pages\CreateCarouselSlide;
use App\Filament\Resources\CarouselSlides\Pages\EditCarouselSlide;
use App\Filament\Resources\CarouselSlides\Pages\ListCarouselSlides;
use App\Filament\Resources\CarouselSlides\Schemas\CarouselSlideForm;
use App\Filament\Resources\CarouselSlides\Tables\CarouselSlidesTable;
use App\Models\CarouselSlide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarouselSlideResource extends Resource
{
    protected static ?string $model = CarouselSlide::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-presentation-chart-bar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management';
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CarouselSlideForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarouselSlidesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarouselSlides::route('/'),
            'create' => CreateCarouselSlide::route('/create'),
            'edit' => EditCarouselSlide::route('/{record}/edit'),
        ];
    }
}
