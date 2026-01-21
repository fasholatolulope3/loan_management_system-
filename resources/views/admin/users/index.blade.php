<x-app-layout>
    <!-- We initialize the modal state and listen for the global event -->
    <div x-data="{ openModal: false }" @open-user-modal.window="openModal = true">

        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('System User Management') }}
                </h2>
                <!-- Dispatches a window-level event -->
                <button @click="$dispatch('open-user-modal')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-bold flex items-center transition">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2" /> Add New Staff
                </button>
            </div>
        </x-slot>

        {{-- @if (session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif --}}

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @include('admin.users.partials.table')
                    </div>
                </div>
            </div>
        </div>

        <!-- CREATE STAFF MODAL -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak style="display: none;">
            <!-- Extra safety for flickering -->

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="openModal = false"></div>

            <!-- Modal Content -->
            <div class="relative flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="openModal = false"
                    x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Add New Staff Member</h3>
                        <button @click="openModal = false"
                            class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="name" value="Full Name" />
                                <x-text-input id="name" name="name" class="block mt-1 w-full" type="text"
                                    required />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email Address" />
                                <x-text-input id="email" name="email" class="block mt-1 w-full" type="email"
                                    required />
                            </div>
                            <div>
                                <x-input-label for="phone" value="Phone Number" />
                                <x-text-input id="phone" name="phone" class="block mt-1 w-full" type="text"
                                    required />
                            </div>
                            <div>
                                <x-input-label for="role" value="Assign Role" />
                                <select name="role"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                    <option value="officer">Loan Officer</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="password" value="Password" />
                                    <x-text-input id="password" name="password" class="block mt-1 w-full"
                                        type="password" required />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" value="Confirm" />
                                    <x-text-input id="password_confirmation" name="password_confirmation"
                                        class="block mt-1 w-full" type="password" required />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="openModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                Cancel
                            </button>
                            <x-primary-button>Create Account</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
