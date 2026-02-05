<x-app-layout>
    <div x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }" @open-user-modal.window="openModal = true">

        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
                    🛡️ System User Management
                </h2>
                <button @click="$dispatch('open-user-modal')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 flex items-center transition">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2 stroke-[3px]" /> Add New Staff
                </button>
            </div>
        </x-slot>

        <!-- Main Content -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Display any Flash Success Messages -->
                {{-- @if (session('success'))
                    <div
                        class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-lg shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif --}}

                <div
                    class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm border border-slate-100 dark:border-slate-700 rounded-[2rem]">
                    <div class="p-8">
                        @include('admin.users.partials.table')
                    </div>
                </div>
            </div>
        </div>

        <!-- CREATE STAFF MODAL -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="openModal = false">
            </div>

            <div class="relative flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100 dark:border-slate-700"
                    x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                    <h3 class="text-xl font-black mb-6 uppercase tracking-tighter">Register New Authority</h3>

                    <!-- 🛑 SHOW MODAL SPECIFIC ERRORS HERE -->
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 text-red-600 rounded-lg text-xs font-bold">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Full Name" />
                                <x-text-input name="name" class="w-full" :value="old('name')" required />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Official Email" />
                                    <x-text-input type="email" name="email" class="w-full" :value="old('email')"
                                        required />
                                </div>
                                <div>
                                    <x-input-label value="Phone" />
                                    <x-text-input name="phone" class="w-full" :value="old('phone')" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Authority Level" />
                                    <select name="role"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-900 dark:text-slate-300">
                                        <option value="officer">Loan Officer</option>
                                        <option value="admin">Administrator</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Assign Center" />
                                    <select name="collation_center_id" required
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-900 dark:text-slate-300">
                                        <option value="">-- Choose --</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t dark:border-slate-700">
                                <div>
                                    <x-input-label value="Password" />
                                    <x-text-input type="password" name="password" class="w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="Confirm Password" />
                                    <x-text-input type="password" name="password_confirmation" class="w-full"
                                        required />
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="openModal = false"
                                class="flex-1 py-3 text-xs font-black uppercase text-slate-400">Cancel</button>
                            <x-primary-button class="flex-[2] justify-center py-4 bg-indigo-600 rounded-2xl">
                                Deploy User
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
