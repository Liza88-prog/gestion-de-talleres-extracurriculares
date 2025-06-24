<x-layout>
  <div class="min-h-screen flex items-center justify-center bg-white">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
      <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6 text-black hover:text-gray-800">
        <x-icons.flyonui />
        <h2 class="text-xl font-bold text-black">Iniciar sesión</h2>
      </a>

      <form wire:submit.prevent="login" class="space-y-6">

        <!-- Correo -->
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
          <div class="relative">
            <input
              type="password"
              id="password"
              wire:model.defer="password"
              placeholder="••••••••"
              class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition pr-10 text-black"
              required
            />
            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <span class="icon-[tabler--eye]" />
            </button>
          </div>
          @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Recordarme -->
        <div class="flex items-center gap-2">
          <input type="checkbox" id="remember" wire:model="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
          <label for="remember" class="text-sm text-black">Recordarme</label>
        </div>
       <div class="mt-4 text-center">
  <p class="text-sm text-black">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Regístrate aquí</a>
  </p>
</div>

        <!-- Botón -->
        <div>
          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow transition"
          >
            Ingresar
          </button>
        </div>
      </form>
    </div>
  </div>
</x-layout>



