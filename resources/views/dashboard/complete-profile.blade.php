<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Account Pending Setup
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-red-600">Profile Incomplete</h3>
                <p class="mt-2 text-gray-600">{{ $message }}</p>
                <p class="mt-4">Please contact an administrator to complete your KYC documentation.</p>
            </div>
        </div>
    </div>
</x-app-layout>
