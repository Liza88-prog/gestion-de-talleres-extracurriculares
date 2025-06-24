<div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 mx-auto">
    <h2 class="text-xl font-bold text-black mb-6 text-center">Registro de Usuario</h2>

    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="register" class="space-y-6">

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-medium text-black mb-1">Nombre completo</label>
            <input
                type="text"
                id="name"
                wire:model.defer="name"
                placeholder="Tu nombre completo"
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-black"
                required
            />
            @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-black mb-1">Correo electrónico</label>
            <input
                type="email"
                id="email"
                wire:model.defer="email"
                placeholder="tucorreo@ejemplo.com"
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-black"
                required
            />
            @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-sm font-medium text-black mb-1">Contraseña</label>
            <input
                type="password"
                id="password"
                wire:model.defer="password"
                placeholder="••••••••"
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-black"
                required
            />
            @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Confirmar Contraseña -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-black mb-1">Confirmar contraseña</label>
            <input
                type="password"
                id="password_confirmation"
                wire:model.defer="password_confirmation"
                placeholder="••••••••"
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-black"
                required
            />
        </div>

        <!-- Botón -->
        <div>
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow transition"
            >
                Registrarse
            </button>
        </div>
    </form>
</div>
