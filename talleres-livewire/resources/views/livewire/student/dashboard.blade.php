<div>
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <h1 class="text-2xl font-bold mb-2">¡Bienvenido, {{ $user->name }}!</h1>
        <p class="text-blue-100">Gestiona tus tareas y talleres desde tu panel personalizado</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tareas Pendientes</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $pendingTasks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Completadas</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $completedTasks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Mis Talleres</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $workshops->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks & My Workshops -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Tasks -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Tareas Recientes</h3>
                <div class="space-y-3">
                    @forelse($recentTasks as $task)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $task->workshop->name ?? 'Sin taller' }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Vence: {{ $task->due_date->format('d/m/Y H:i') }}
                                    @if($task->isOverdue())
                                        <span class="text-red-500 font-medium">(Vencida)</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->priority_color }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                @if($task->status !== 'completed')
                                    <button wire:click="markTaskCompleted({{ $task->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-green-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No tienes tareas asignadas</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- My Workshops -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Mis Talleres</h3>
                <div class="space-y-3">
                    @forelse($workshops as $workshop)
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $workshop->name }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $workshop->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $workshop->status === 'active' ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $workshop->instructor }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $workshop->location }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $workshop->start_date->format('d/m/Y') }} - {{ $workshop->end_date->format('d/m/Y') }}
                                </span>
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                    {{ $workshop->tasks->where('assigned_to', $user->id)->count() }} tareas
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No estás inscrito en ningún taller</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Overview -->
    @if($totalTasks > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Progreso General</h3>
                <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ ($completedTasks / $totalTasks) * 100 }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span>{{ $completedTasks }} de {{ $totalTasks }} tareas completadas</span>
                    <span>{{ round(($completedTasks / $totalTasks) * 100) }}%</span>
                </div>
            </div>
        </div>
    @endif
</div>