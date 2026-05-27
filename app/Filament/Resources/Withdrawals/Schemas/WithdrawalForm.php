<?php

namespace App\Filament\Resources\Withdrawals\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WithdrawalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Penarikan')
                    ->aside()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('id_campaign')
                                ->label('Kampanye')
                                ->relationship('campaign', 'title')
                                ->required()
                                ->disabled(),
                            Select::make('id_user')
                                ->label('Pengguna')
                                ->relationship('user', 'full_name')
                                ->required()
                                ->disabled(),
                            TextInput::make('amount')
                                ->label('Jumlah Penarikan')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->disabled(),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'pending' => 'Menunggu',
                                    'under_review' => 'Sedang Ditinjau',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'paid' => 'Dibayar',
                                ])
                                ->required(),
                        ]),
                    ]),

                Section::make('Informasi Rekening')
                    ->aside()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('bank_name')
                                ->label('Nama Bank')
                                ->disabled(),
                            TextInput::make('account_number')
                                ->label('Nomor Rekening')
                                ->disabled(),
                            TextInput::make('account_holder')
                                ->label('Atas Nama')
                                ->disabled(),
                        ]),
                    ]),

                Section::make('Catatan')
                    ->aside()
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan Pengguna')
                            ->disabled()
                            ->rows(2),
                        Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->helperText('Catatan ini akan terlihat oleh pengguna.'),
                    ]),
            ]);
    }
}
