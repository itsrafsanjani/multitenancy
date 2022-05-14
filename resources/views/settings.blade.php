<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('settings.store') }}">
                        @csrf

                        <!-- Company -->
                        <div>
                            <x-label for="company" :value="__('Company')" />

                            <x-input id="company" class="block mt-1 w-full" type="text" name="company" :value="$tenant . data . company"
                                required autofocus />
                        </div>

                        <!-- Domain -->
                        <div class="mt-4">
                            <x-label for="domain" :value="__('Domain')" />

                            <x-input id="domain" class="block mt-1 w-full" type="text" name="domain" required
                                autocomplete="domain" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
