<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kampanye')
                    ->description('Informasi utama mengenai kampanye penggalangan dana Anda.')
                    ->aside()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Kampanye')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),
                                TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('Slug dibuat otomatis dari judul untuk alamat URL kampanye.'),
                                TextInput::make('target_amount')
                                    ->label('Target Donasi')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(1000)
                                    ->maxLength(15),
                                Select::make('status')
                                    ->label('Status Kampanye (Hanya Admin)')
                                    ->options([
                                        'draft' => 'Draft',
                                        'pending' => 'Menunggu Review (Pending)',
                                        'approved' => 'Disetujui (Approved)',
                                        'rejected' => 'Ditolak (Rejected)',
                                        'completed' => 'Selesai (Completed)',
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->disabled(fn () => !auth()->user()->isAdmin())
                                    ->dehydrated(fn ($state) => auth()->user()->isAdmin() || filled($state))
                                    ->helperText('Hanya administrator yang dapat mengubah status kampanye.'),
                            ]),
                    ]),

                Section::make('Konten & Media')
                    ->description('Jelaskan tujuan kampanye Anda dan unggah gambar yang representatif.')
                    ->aside()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                FileUpload::make('banner_image')
                                    ->label('Gambar Sampul Kampanye')
                                    ->image()
                                    ->directory('campaign-banners')
                                    ->required(),
                                RichEditor::make('description')
                                    ->label('Deskripsi Lengkap Kampanye')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'heading',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ]),
                            ]),
                    ]),
            ]);
    }
}

