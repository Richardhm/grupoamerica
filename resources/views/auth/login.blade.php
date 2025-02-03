<x-guest-layout>


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="flex justify-center">
        <img src="{{asset('logo_bm_1.png')}}" style="" alt="BmSys">
    </div>

    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm mx-auto px-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label class="text-white" for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-[rgba(254,254,254,0.18)] text-black backdrop-blur-[15px]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label class="text-white" for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full bg-[rgba(254,254,254,0.18)] text-black backdrop-blur-[15px]" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-white dark:text-white">{{ __('Lembra-me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button class="w-full text-center py-2.5 px-5 me-2 mb-2 text-sm font-medium text-white rounded-lg border border-gray-200 focus:z-10" style="background-color:#E0FFFF;color:black;">
                {{ __('Login') }}
            </button>
        </div>
    </form>
</x-guest-layout>
