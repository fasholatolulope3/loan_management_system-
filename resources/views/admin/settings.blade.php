<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- General Settings -->
                    <section>
                        <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-600">Organization Info</h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Organization Name" />
                                <x-text-input class="w-full" value="LMS Professional" disabled />
                            </div>
                            <div>
                                <x-input-label value="Currency Symbol" />
                                <x-text-input class="w-full" value="₦ (NGN)" disabled />
                            </div>
                        </div>
                    </section>

                    <!-- Financial Rules -->
                    <section>
                        <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-600">Global Financial Rules</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <span>Enable Automatic Penalty Calculation</span>
                                <span class="text-green-600 font-bold uppercase text-xs">Enabled</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <span>Loan Approval Workflow</span>
                                <span class="text-blue-600 font-bold uppercase text-xs">Manual (Officer)</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
