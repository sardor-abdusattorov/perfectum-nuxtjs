<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <x-filament::avatar
                    :src="filament()->getUserAvatarUrl(auth()->user())"
                    :alt="auth()->user()->name"
                    size="lg"
                />
                <div>
                    <h2 class="text-xl font-semibold text-gray-950 dark:text-white">
                        {{ $this->getGreeting() }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $this->getUserName() }}
                    </p>
                </div>
            </div>
            <form action="{{ filament()->getLogoutUrl() }}" method="post">
                @csrf
                <x-filament::button
                    type="submit"
                    color="gray"
                    icon="heroicon-o-arrow-right-start-on-rectangle"
                >
                    {{ __('app.greeting.sign_out') }}
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
