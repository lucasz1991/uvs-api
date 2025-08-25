@extends('layouts.master-without-nav')
@section('title')
    Register
@endsection
@section('content')
    <div class="my-auto">
        <div class="text-center">
            <h5 class="text-gray-600 dark:text-gray-100">Registriere ein Account</h5>
        </div>
        <form method="POST" action="{{ route('register') }}" class="mt-4 pt-2">
            @csrf
            <div class="mb-4">
                <label class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Name <span
                        class="text-red-600">*</span></label>
                <input id="name" type="text" name="name" :value="old('name')"
                    type="text"
                    class="w-full border-gray-100 rounded placeholder:text-sm py-2 px-1 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                    id="name" placeholder="Name eingeben" required>
                @error('name')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Email <span
                        class="text-red-600">*</span></label>
                <input id="email" type="email" name="email" :value="old('email')"
                    type="text"
                    class="w-full border-gray-100 rounded placeholder:text-sm py-2 px-1 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                    id="email" placeholder="Email eingeben" required>
                @error('email')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <div>
                    <div class="flex-grow-1">
                        <label for="password"
                            class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Passwort
                            <span class="text-red-600">*</span></label>
                    </div>
                </div>
                <div class="flex">
                    <input type="password" id="password" name="password" required
                        class="w-full border-gray-100 rounded ltr:rounded-r-none rtl:rounded-l-none placeholder:text-sm py-2 px-1 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                        placeholder="Passwort eingeben " aria-label="Password"
                        aria-describedby="password-addon">
                </div>
                @error('password')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <div>
                    <div class="flex-grow-1">
                        <label for="password_confirmation"
                            class="text-gray-600 font-medium mb-2 block dark:text-gray-100">Passwort bestätigen <span class="text-red-600">*</span></label>
                    </div>
                </div>
                <div class="flex">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        required
                        class="w-full border-gray-100 rounded ltr:rounded-r-none rtl:rounded-l-none placeholder:text-sm py-2 px-1 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-gray-100 dark:placeholder:text-zinc-100/60"
                        placeholder="Passwort bestätigen " aria-label="Password"
                        aria-describedby="password-addon">
                </div>
            </div>
            <div class="row mb-6">
                <div class="col">
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />
                                    <div class="ml-2">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' =>
                                                '<a target="_blank" href="' .
                                                route('terms.show') .
                                                '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                                __('Terms of Service') .
                                                '</a>',
                                            'privacy_policy' =>
                                                '<a target="_blank" href="' .
                                                route('policy.show') .
                                                '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                                __('Privacy Policy') .
                                                '</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <button
                    class="btn border-transparent bg-blue-50 w-full py-2.5 text-blue-200 text-lg w-100 waves-effect waves-light shadow-md shadow-gray-200 dark:shadow-zinc-600"
                    type="submit">Registrieren </button>
            </div>
        </form>
        <div class="mt-12 text-center">
            <p class="text-gray-500 dark:text-zinc-100/60">Du hast schon ein Account ? <a
                    href="{{ route('login') }}" class="text-blue-200 font-semibold"> Einloggen </a>
            </p>
        </div>
    </div>
@endsection
