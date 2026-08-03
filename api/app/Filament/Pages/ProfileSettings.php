<?php

namespace App\Filament\Pages;

use App\Filament\Support\ImageUpload;
use App\Services\UserSessions;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileSettings extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected string $view = 'filament.pages.profile-settings';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'profile';

    /** @var array<string, mixed> */
    public ?array $profileData = [];

    /** @var array<string, mixed> */
    public ?array $passwordData = [];

    public function getTitle(): string
    {
        return __('app.label.my_profile');
    }

    public function getSubheading(): ?string
    {
        return __('app.label.manage_profile');
    }

    public function mount(): void
    {
        $user = Auth::user();

        $this->profileForm->fill([
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
        ]);

        $this->passwordForm->fill();
    }

    public function profileForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.label.personal_information'))
                    ->description(__('app.label.personal_information_description'))
                    ->aside()
                    ->schema([
                        ImageUpload::make('users', 'avatar_url')
                            ->label(__('app.label.avatar'))
                            ->avatar()
                            ->imageEditorAspectRatios(['1:1']),

                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('app.label.first_name'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label(__('app.label.email'))
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email', ignorable: Auth::user())
                                    ->maxLength(255),
                            ]),
                    ])
                    ->footerActions([
                        Action::make('save_profile')
                            ->label(__('app.action.update'))
                            ->color('primary')
                            ->submit('updateProfile'),
                    ])
                    ->footerActionsAlignment(Alignment::End),
            ])
            ->statePath('profileData');
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('app.label.password'))
                    ->description(__('app.label.password_description'))
                    ->aside()
                    ->schema([
                        TextInput::make('current_password')
                            ->label(__('app.label.current_password'))
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),

                        TextInput::make('password')
                            ->label(__('app.label.new_password'))
                            ->password()
                            ->required()
                            ->rule(Password::min(8))
                            ->revealable()
                            ->autocomplete('new-password'),

                        TextInput::make('password_confirmation')
                            ->label(__('app.label.password_confirmation'))
                            ->password()
                            ->required()
                            ->same('password')
                            ->revealable()
                            ->autocomplete('new-password'),
                    ])
                    ->footerActions([
                        Action::make('save_password')
                            ->label(__('app.action.update'))
                            ->color('primary')
                            ->submit('updatePassword'),
                    ])
                    ->footerActionsAlignment(Alignment::End),
            ])
            ->statePath('passwordData');
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->profileForm->getState();
        } catch (Halt $exception) {
            return;
        }

        Auth::user()->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar_url' => $data['avatar_url'] ?? null,
        ]);

        Notification::make()
            ->title(__('app.notification.profile_updated'))
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->passwordForm->getState();
        } catch (Halt $exception) {
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->passwordForm->fill();

        Notification::make()
            ->title(__('app.notification.password_updated'))
            ->success()
            ->send();
    }

    public function logoutOtherSessionsAction(): Action
    {
        return Action::make('logoutOtherSessions')
            ->label(__('app.action.logout_other_sessions'))
            ->color('danger')
            ->icon('heroicon-o-arrow-right-on-rectangle')
            ->requiresConfirmation()
            ->modalHeading(__('app.action.logout_other_sessions'))
            ->modalDescription(__('app.notification.confirm_logout_other_sessions'))
            ->modalSubmitActionLabel(__('app.action.confirm'))
            ->schema([
                TextInput::make('password')
                    ->label(__('app.label.password'))
                    ->password()
                    ->required()
                    ->currentPassword()
                    ->revealable(),
            ])
            ->action(function (array $data): void {
                if (! app(UserSessions::class)->logoutOthers($data['password'])) {
                    Notification::make()
                        ->title(__('app.notification.session_driver_not_supported'))
                        ->danger()
                        ->send();

                    return;
                }

                Notification::make()
                    ->title(__('app.notification.other_sessions_logged_out'))
                    ->success()
                    ->send();
            });
    }

    /**
     * @return array<int, array{id: string, ip_address: ?string, browser: string, platform: string, is_current_device: bool, last_active: string}>
     */
    public function getSessions(): array
    {
        return app(UserSessions::class)->forCurrentUser();
    }
}
