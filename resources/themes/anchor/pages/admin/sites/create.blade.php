<?php
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use function Laravel\Folio\{middleware, name};
use Illuminate\Support\Str;
use App\Models\Site;

middleware('auth');
name('admin.sites.create');

new class extends Component {
    #[Validate('required|min:3|max:255')]
    public $name = '';

    #[Validate('required|url')]
    public $domain = '';

    public function save()
    {
        $validated = $this->validate();

        // Створення API ключа
        $apiKey = auth()->user()->createApiKey(Str::slug($this->name));

        // Створення сайту
        Site::create([
            'name' => $this->name,
            'domain' => $this->domain,
            'user_id' => auth()->id(),
            'api_key_id' => $apiKey->id,
        ]);

        session()->flash('message', 'Site created successfully.');
        $this->redirect(route('admin.sites'));
    }
}
?>
<x-layouts.app>
    @volt('admin.sites.create')
        <x-app.container>
            <x-elements.back-button text="Back to Sites" :href="route('admin.sites')" />

            <x-app.heading title="Add New Site" :border="false" />

            <form wire:submit="save" class="mt-4 space-y-4">
                <div>
                    <label>Name</label>
                    <input wire:model="name" type="text" class="w-full" />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label>Domain (e.g., https://example.com)</label>
                    <input wire:model="domain" type="url" class="w-full" />
                    @error('domain') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <x-button type="submit">Create Site</x-button>
            </form>
        </x-app.container>
    @endvolt
</x-layouts.app>
