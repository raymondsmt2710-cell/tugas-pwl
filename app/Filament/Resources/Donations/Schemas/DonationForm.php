<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Donasi')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('id_campaign')
                                ->label('Kampanye')
                                ->relationship('campaign', 'title')
                                ->required(),
                            Select::make('id_user')
                                ->label('Pengguna (Donatur Terdaftar)')
                                ->relationship('user', 'full_name')
                                ->nullable()
                                ->placeholder('Anonim / Guest'),
                            TextInput::make('donor_name')
                                ->label('Nama Donatur')
                                ->required()
                                ->maxLength(100),
                            TextInput::make('donor_email')
                                ->label('Email Donatur')
                                ->email()
                                ->required()
                                ->maxLength(100),
                            TextInput::make('donation_amount')
                                ->label('Jumlah Donasi')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options([
                                    'pending' => 'Menunggu Pembayaran',
                                    'paid' => 'Lunas / Sukses',
                                    'failed' => 'Gagal',
                                    'expired' => 'Kedaluwarsa',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->required()
                                ->default('pending'),
                            Select::make('payment_method')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'credit_card' => 'Kartu Kredit',
                                    'bank_transfer' => 'Transfer Bank',
                                    'e_wallet' => 'Dompet Digital (E-Wallet)',
                                    'gopay' => 'GoPay',
                                    'shopeepay' => 'ShopeePay',
                                    'qris' => 'QRIS',
                                ])
                                ->nullable(),
                            DateTimePicker::make('paid_at')
                                ->label('Waktu Pembayaran')
                                ->nullable(),
                            Toggle::make('is_anonymous')
                                ->label('Donasi sebagai Anonim')
                                ->default(false),
                        ]),
                        Textarea::make('donor_message')
                            ->label('Pesan / Doa dari Donatur')
                            ->rows(3)
                            ->nullable(),
                    ]),
            ]);
    }
}
