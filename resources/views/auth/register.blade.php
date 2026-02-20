<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- National ID (NIN) -->
        <div class="mt-4">
            <x-input-label for="national_id" :value="__('National ID (NIN)')" />
            <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id"
                :value="old('national_id')" required />
            <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required
                autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Residential Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Residential Address')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')"
                required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <!-- Date of Birth -->
            <div>
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth"
                    :value="old('date_of_birth')" required />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>
            <!-- Monthly Income -->
            <div>
                <x-input-label for="income" :value="__('Estimated Monthly Income (₦)')" />
                <x-text-input id="income" class="block mt-1 w-full" type="number" name="income" :value="old('income')"
                    required />
                <x-input-error :messages="$errors->get('income')" class="mt-2" />
            </div>
        </div>

        <!-- Employment Status -->
        <div class="mt-4">
            <x-input-label for="employment_status" :value="__('Employment Status')" />
            <select name="employment_status" id="employment_status"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900">
                <option value="Self-Employed">Self-Employed / Business Owner</option>
                <option value="Private Sector">Private Sector Employee</option>
                <option value="Civil Servant">Civil Servant / Government</option>
                <option value="Unemployed">Other / Unemployed</option>
            </select>
            <x-input-error :messages="$errors->get('employment_status')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>