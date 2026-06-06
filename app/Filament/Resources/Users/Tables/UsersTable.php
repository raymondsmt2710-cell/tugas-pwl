<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
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
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin'       => 'warning',
                        'user'        => 'info',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'super_admin' => 'Super Admin',
                        'admin'       => 'Admin',
                        'user'        => 'User',
                        default       => $state,
                    })
                    ->sortable(),
                TextColumn::make('account_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'    => 'success',
                        'suspended' => 'danger',
                        'pending'   => 'warning',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'    => 'Aktif',
                        'suspended' => 'Ditangguhkan',
                        'pending'   => 'Menunggu',
                        default     => $state,
                    })
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
                        'admin'       => 'Administrator',
                        'user'        => 'Pengguna Biasa',
                    ]),
                SelectFilter::make('account_status')
                    ->label('Saring Status')
                    ->options([
                        'active'    => 'Aktif',
                        'suspended' => 'Ditangguhkan',
                        'pending'   => 'Menunggu',
                    ]),
            ])
            ->recordActions([
                // Ubah Peran
                Action::make('changeRole')
                    ->label('Ubah Peran')
                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Ubah Peran Pengguna')
                    ->modalDescription(fn (User $record): string => "Pilih peran baru untuk pengguna \"{$record->full_name}\".")
                    ->modalSubmitActionLabel('Ya, Ubah Peran')
                    ->form([
                        Select::make('role')
                            ->label('Peran')
                            ->options([
                                'super_admin' => 'Super Admin',
                                'admin'       => 'Admin',
                                'user'        => 'User',
                            ])
                            ->required()
                            ->default(fn (User $record): string => $record->role),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update(['role' => $data['role']]);

                        $label = match ($data['role']) {
                            'super_admin' => 'Super Admin',
                            'admin'       => 'Admin',
                            default       => 'User',
                        };

                        Notification::make()
                            ->title('Peran Diperbarui')
                            ->body("Peran \"{$record->full_name}\" berhasil diubah menjadi {$label}.")
                            ->success()
                            ->send();
                    }),

                // Ubah Status
                Action::make('changeStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-user-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Ubah Status Akun')
                    ->modalDescription(fn (User $record): string => "Pilih status baru untuk akun \"{$record->full_name}\".")
                    ->modalSubmitActionLabel('Ya, Ubah Status')
                    ->form([
                        Select::make('account_status')
                            ->label('Status Akun')
                            ->options([
                                'active'    => 'Aktif',
                                'suspended' => 'Ditangguhkan',
                                'pending'   => 'Menunggu',
                            ])
                            ->required()
                            ->default(fn (User $record): string => $record->account_status),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update(['account_status' => $data['account_status']]);

                        $label = match ($data['account_status']) {
                            'active'    => 'Aktif',
                            'suspended' => 'Ditangguhkan',
                            default     => 'Menunggu',
                        };

                        Notification::make()
                            ->title('Status Akun Diperbarui')
                            ->body("Status akun \"{$record->full_name}\" berhasil diubah menjadi {$label}.")
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
