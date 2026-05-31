<?php

namespace App\Filament\Resources\CarouselSlides\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CarouselSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->description('General slide background, branding, and text configurations.')
                    ->schema([
                        TextInput::make('subtitle')
                            ->label('Slide Subtitle (e.g. SEAL)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. SEAL ONLINE'),

                        TextInput::make('title')
                            ->label('Slide Title (e.g. /// GENESIS ///)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. /// GENESIS ///'),
                        
                        FileUpload::make('image')
                            ->label('Full-Bleed Background Image')
                            ->disk('uploads_public')
                            ->directory('carousel')
                            ->image()
                            ->required()
                            ->helperText('Upload a high-quality widescreen character wallpaper for the slide background.'),

                        TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Is Active?')
                            ->default(true),
                    ]),

                Section::make('Box 1 Card (Left)')
                    ->collapsible()
                    ->schema([
                        TextInput::make('box1_label')
                            ->label('Card Header (e.g. TEAM AYVI)')
                            ->placeholder('e.g. 1st TEAM AYVI'),
                        Textarea::make('box1_value')
                            ->label('Card Roster/Content (Support multi-lines)')
                            ->rows(4)
                            ->placeholder("e.g.\n• Player 1 (Archer)\n• Player 2 (Renegade)\n• Player 3 (Gunner)"),
                    ]),

                Section::make('Box 2 Card (Center)')
                    ->collapsible()
                    ->schema([
                        TextInput::make('box2_label')
                            ->label('Card Header (e.g. TEAM OHMAGAD)')
                            ->placeholder('e.g. 2nd TEAM OHMAGAD'),
                        Textarea::make('box2_value')
                            ->label('Card Roster/Content (Support multi-lines)')
                            ->rows(4)
                            ->placeholder("e.g.\n• Player 1 (Archer)\n• Player 2 (Renegade)\n• Player 3 (Gunner)"),
                    ]),

                Section::make('Box 3 Card (Right)')
                    ->collapsible()
                    ->schema([
                        TextInput::make('box3_label')
                            ->label('Card Header (e.g. TEAM AUSRINE)')
                            ->placeholder('e.g. 3rd TEAM AUSRINE'),
                        Textarea::make('box3_value')
                            ->label('Card Roster/Content (Support multi-lines)')
                            ->rows(4)
                            ->placeholder("e.g.\n• Player 1 (Archer)\n• Player 2 (Renegade)\n• Player 3 (Gunner)"),
                    ]),
            ]);
    }
}
