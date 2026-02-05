<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Details
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-700">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $user->role === 'officer' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'client' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="flex items-center text-sm {{ $user->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                            <span class="h-2 w-2 rounded-full bg-current mr-2"></span>
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center space-x-2">
                            <form action="{{ route('users.update', $user) }}" method="POST">
                                @csrf @method('PATCH')
                                <select onchange="this.form.submit()" name="status"
                                    class="text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Activate
                                    </option>
                                    <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>
                                        Suspend</option>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                <th class="px-6 py-3 text-left text-xs font-black uppercase text-slate-500 tracking-widest">
                    Assigned Center
                </th>

                <!-- Inside the foreach loop -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $user->collationCenter?->name ?? 'SYSTEM MASTER' }}
                        </span>
                        <span
                            class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">
                            {{ $user->collationCenter?->center_code ?? 'NO-ZONE' }}
                        </span>
                    </div>
                </td>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                        No users found in the system.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
