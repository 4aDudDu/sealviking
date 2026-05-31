<?php

namespace App\Filament\Resources\News;

use App\Filament\Resources\News\Pages;
use App\Models\News;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    // Pakai function agar terhindar dari error tipe data BackedEnum/UnitEnum
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-newspaper';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Berita')->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Berita')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Singkat')
                        ->rows(2)
                        ->placeholder('Muncul di list halaman depan...'),
                    
                    Forms\Components\RichEditor::make('content')
                        ->label('Isi Konten Lengkap'),
                    
                    Forms\Components\Toggle::make('is_hot')
                        ->label('Tandai sebagai HOT News?'),
                    
                    Forms\Components\DatePicker::make('published_at')
                        ->label('Tanggal Publikasi')
                        ->default(now()),
                ]),
                Section::make('Media')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Banner')
                        ->disk('uploads_public') 
                        ->directory('news')
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
                Tables\Columns\IconColumn::make('is_hot')
                    ->boolean()
                    ->label('Hot?'),
                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->label('Tanggal'),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}