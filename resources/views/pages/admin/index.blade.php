<x-layouts::app :title="__('Learning Administrator Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Learning Administrator Dashboard</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                Manage foundational platform actors before moving into training lifecycle modules.
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Admin Tools</h2>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                Start with user and role management so coordinator, SME, employee, and helpdesk accounts are ready for the next modules.
            </p>
            <div class="mt-4">
                <a
                    href="{{ route('users.index') }}"
                    class="inline-flex items-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-neutral-900"
                >
                    Open User Management
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
