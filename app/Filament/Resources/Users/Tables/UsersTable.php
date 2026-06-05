<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name) . '&color=7F9CF5&background=EBF4FF'),
                ImageColumn::make('cover_photo_path')
                    ->label('Foto Sampul')
                    ->disk('public')
                    ->height(36)
                    ->width(64)
                    ->extraImgAttributes(['style' => 'border-radius:6px;object-fit:cover;'])
                    ->defaultImageUrl('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=60')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->placeholder('-'),
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('phone_number')
                    ->label('No. Telepon')
                    ->searchable()
                    ->placeholder('-'),
                SelectColumn::make('role')
                    ->label('Peran')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin',
                        'user' => 'User',
                    ])
                    ->sortable(),
                SelectColumn::make('account_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ])
                    ->sortable(),
                ToggleColumn::make('is_verified')
                    ->label('Centang Biru')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Terdaftar Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Saring Peran')
                    ->options([
                        'super_admin' => 'Super Administrator',
                        'admin' => 'Administrator',
                        'user' => 'Pengguna Biasa',
                    ]),
                SelectFilter::make('account_status')
                    ->label('Saring Status')
                    ->options([
                        'active' => 'Aktif',
                        'suspended' => 'Ditangguhkan',
                        'pending' => 'Menunggu',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

