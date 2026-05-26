<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <x-authentication-card-logo />
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary/80">
                    Log in here
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-sm sm:rounded-2xl sm:px-10 border border-gray-100">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="name" value="{{ __('Full Name') }}" class="text-gray-700 font-semibold" />
                        <div class="mt-1">
                            <x-input id="name" class="block w-full rounded-xl border-gray-300 focus:ring-primary focus:border-primary shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>
                    </div>

                    <div>
                        <x-label for="email" value="{{ __('Email Address') }}" class="text-gray-700 font-semibold" />
                        <div class="mt-1">
                            <x-input id="email" class="block w-full rounded-xl border-gray-300 focus:ring-primary focus:border-primary shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        </div>
                    </div>

                    <div>
                        <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 font-semibold" />
                        <div class="mt-1">
                            <x-input id="password" class="block w-full rounded-xl border-gray-300 focus:ring-primary focus:border-primary shadow-sm" type="password" name="password" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-700 font-semibold" />
                        <div class="mt-1">
                            <x-input id="password_confirmation" class="block w-full rounded-xl border-gray-300 focus:ring-primary focus:border-primary shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required class="rounded-md border-gray-300 text-primary focus:ring-primary" />
                            <div class="ml-2">
                                <label for="terms" class="text-sm text-gray-600">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline hover:text-gray-900">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </label>
                            </div>
                        </div>
                    @endif

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-black bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                            {{ __('Create Account') }}
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500 font-medium">Or register with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a href="{{ route('social.login', 'google') }}" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-xl shadow-xs bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Google
                        </a>
                        <a href="{{ route('social.login', 'github') }}" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-xl shadow-xs bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                            </svg>
                            GitHub
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
