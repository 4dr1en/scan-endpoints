<section>
    <header>
        <h2 class="">
            {{ __('Profile Information') }}
        </h2>

        <p class="">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="edit-profile__displayname">
            <x-input-label for="displayname" :value="__('Display Name')" />
            <x-text-input id="displayname" name="display_name" type="text" class="" :value="old('display_name', $user->display_name)" autofocus
                autocomplete="displayname" />
            <x-input-error class="" :messages="$errors->get('display_name') ?: $errors->get('display_name')" />
        </div>

        <div class="edit-profile__firstname">
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" name="first_name" type="text" class="" :value="old('first_name', $user->first_name)" required
                autofocus autocomplete="firstname" />
            <x-input-error class="" :messages="$errors->get('first_name')" />
        </div>

        <div class="edit-profile__lastname">
            <x-input-label for="lastname" :value="__('Last Name')" />
            <x-text-input id="lastname" name="last_name" type="text" class="" :value="old('last_name', $user->last_name)" required
                autofocus autocomplete="lastname" />
            <x-input-error class="" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-input-error class="" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <br>
        <div class="">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
