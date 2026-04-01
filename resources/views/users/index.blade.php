<x-layouts::app :title="__('User Management')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex items-center justify-between rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">User Management</h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                    Create and maintain the business actors needed by the LMS Marketplace workflow.
                </p>
            </div>

            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-neutral-900"
            >
                Create User
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full table-fixed divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-200 dark:bg-neutral-800">
                    <tr>
                        <th class="w-1/5 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-900 dark:text-neutral-100">Name</th>
                        <th class="w-2/5 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-900 dark:text-neutral-100">Email</th>
                        <th class="w-1/5 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-900 dark:text-neutral-100">Role</th>
                        <th class="w-1/5 px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-900 dark:text-neutral-100">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-neutral-50 dark:divide-neutral-800 dark:bg-neutral-950">
                    @forelse ($users as $user)
                        <tr class="bg-neutral-50 dark:bg-neutral-950">
                            <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $user->name }}</td>
                            <td class="break-words px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $user->email }}</td>
                            <td class="break-words px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $user->role }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="text-sm font-medium text-neutral-900 underline dark:text-neutral-100"
                                    >
                                        Edit
                                    </a>
                                    
                                    @if (auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 underline dark:text-red-400">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-neutral-50 dark:bg-neutral-950">
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-neutral-600 dark:text-neutral-300">
                                No users found. Run the seeder or create the first user from this page.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
