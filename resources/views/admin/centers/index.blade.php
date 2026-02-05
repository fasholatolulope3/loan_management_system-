<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl dark:text-white">Collation Centers Management</h2>
    </x-slot>

    <div class="py-12" x-data="{ showForm: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <button @click="showForm = !showForm" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold mb-6">
                + Create New Center
            </button>

            <!-- Quick Create Form -->
            <div x-show="showForm" class="bg-white dark:bg-slate-800 p-6 rounded-xl mb-6 shadow-sm">
                <form method="POST" action="{{ route('admin.centers.store') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <x-input-label value="Center Name" />
                        <x-text-input name="name" class="w-full" required />
                    </div>
                    <div>
                        <x-input-label value="Center Code" />
                        <x-text-input name="center_code" class="w-full" required placeholder="e.g. LAGOS-01" />
                    </div>
                    <div>
                        <x-input-label value="Physical Address" />
                        <x-text-input name="address" class="w-full" required />
                    </div>
                    <div class="md:col-span-3">
                        <x-primary-button>Register Center</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Centers List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-black uppercase text-slate-500">Center Name</th>
                            <th class="px-6 py-3 text-left text-xs font-black uppercase text-slate-500">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-black uppercase text-slate-500">Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach ($centers as $center)
                            <tr class="dark:text-slate-300">
                                <td class="px-6 py-4 font-bold">{{ $center->name }}</td>
                                <td class="px-6 py-4">{{ $center->center_code }}</td>
                                <td class="px-6 py-4 text-sm">{{ $center->address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
