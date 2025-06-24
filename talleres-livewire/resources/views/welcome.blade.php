<x-layout>
<div class="flex flex-col-reverse lg:flex-row items-center justify-center h-screen">
  <div class="card sm:card-side grid lg:grid-cols-2 lg:max-w-4xl max-w-[335px] w-full">
    <div class="card-body justify-center">
      <h5 class="card-title mb-0.5">FlyonUI Laravel Livewire Starter Kit</h5>
      <span class="mb-2">
        Seamlessly integrate <strong>FlyonUI</strong> components into your <strong>Laravel Livewire</strong> projects. Kickstart development with pre-built UI elements and <strong>Headless JS</strong> components. Explore our live documentation and demos for a detailed guide.
      </span>

      <ul class="space-y-3">

        <li class="flex items-center space-x-3">
          <span class="bg-primary/20 text-primary flex items-center justify-center rounded-full p-1">
            <span class="icon-[tabler--arrow-right] size-4 rtl:rotate-180"></span>
          </span>
          <span class="text-base-content">
            Browse the <livewire:simple-link :link="'https://flyonui.com/components/'" :link-text="'FlyonUI Components'" />
          </span>
        </li>

        <li class="flex items-center space-x-3">
          <span class="bg-primary/20 text-primary flex items-center justify-center rounded-full p-1">
            <span class="icon-[tabler--arrow-right] size-4 rtl:rotate-180"></span>
          </span>
          <span class="text-base-content">
            Get started with FlyonUI Laravel <livewire:simple-link :link="'https://flyonui.com/framework-integrations/laravel/'" :link-text="'Installation'" />
          </span>
        </li>

        <li class="flex items-center space-x-3">
          <span class="bg-primary/20 text-primary flex items-center justify-center rounded-full p-1">
            <span class="icon-[tabler--arrow-right] size-4 rtl:rotate-180"></span>
          </span>
          <span class="text-base-content">
            Discover more <livewire:simple-link :link="'https://themeselection.com/item/category/tailwind-css-templates/'" :link-text="'Starter Kit'" />
          </span>
        </li>
      </ul>

      <div class="card-actions mt-4">
        <livewire:browse-button />
      </div>
    </div>
    <figure>
      <img class="card-img card-img-right" src="{{ asset('assets/img/illustrations/laravel-livewire-flyonui.png') }}" alt="Laravel Livewire with FlyonUI">
    </figure>
  </div>


    </div>
</x-layout>
