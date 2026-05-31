<?php

namespace App\Filament\Resources\Event;

use App\Filament\Resources\Event\Pages;
use App\Models\Event;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Event')->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Event')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Singkat')
                        ->rows(2)
                        ->placeholder('Deskripsi singkat untuk event...'),
                    
                    Forms\Components\RichEditor::make('content')
                        ->label('Isi Konten Event'),
                    
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true),
                    
                    Forms\Components\DatePicker::make('event_date')
                        ->label('Tanggal Event')
                        ->required()
                        ->default(now()),
                ]),
                Section::make('Media')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Banner')
                        ->disk('uploads_public') 
                        ->directory('events')
                        ->image()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('uploads_public')
                    ->label('Banner'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif?'),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Event'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
