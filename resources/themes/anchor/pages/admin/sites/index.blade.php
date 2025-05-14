<?php
    use function Laravel\Folio\{middleware, name};
    use Livewire\Attributes\Validate;
    use Livewire\Volt\Component;
    middleware('auth');
    name('admin.sites.create');

    new class extends Component
    {
        #[Validate('required|string|max:255')]
        public $name = '';

        #[Validate('required|url')]
        public $domain = '';

        public function save()
        {
            // Валідуємо дані
            $validated = $this->validate();

            // Створюємо новий сайт для поточного користувача
            $site = auth()->user()->sites()->create([
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'api_key_id' => $this->createApiKey(),  // Генерація API ключа
            ]);

            session()->flash('message', 'Site created successfully.');
            $this->redirect(route('admin.sites'));
        }

        // Генерація API ключа для сайту
        protected function createApiKey()
        {
            $apiKey = auth()->user()->createApiKey(Str::slug($this->name));
            return $apiKey->id;
        }
    }
?>

<x-layouts.app>
    @volt('admin.sites.create')
        <x-app.container>

            <x-elements.back-button
                class="max-w-full mx-auto mb-3"
                text="Back to Sites"
                :href="route('admin.sites')"
            />

            <div class="flex items-center justify-between mb-3">
                <x-app.heading
                        title="New Site"
                        description="Create a new site for indexing"
                        :border="false"
                    />
            </div>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Site Name</label>
                    <input type="text" id="name" wire:model="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="domain" class="block mb-2 text-sm font-medium text-gray-700">Domain</label>
                    <input type="text" id="domain" wire:model="domain" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('domain') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-button type="submit">
                        Create Site
                    </x-button>
                </div>
            </form>
        </x-app.container>
    @endvolt
</x-layouts.app>
