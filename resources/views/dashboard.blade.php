<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'card-created')
                <div class="bg-green-100 text-green-800 text-sm rounded-lg p-4">
                    {{ __('Foto adicionada com sucesso.') }}
                </div>
            @elseif (session('status') === 'card-deleted')
                <div class="bg-green-100 text-green-800 text-sm rounded-lg p-4">
                    {{ __('Foto removida com sucesso.') }}
                </div>
            @endif

            @can('create', \App\Models\Card::class)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Adicionar nova foto') }}
                        </h3>

                        <form method="POST" action="{{ route('cards.store') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="title" :value="__('Título (opcional)')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="image" :value="__('Imagem')" />
                                <input id="image" name="image" type="file" accept="image/*" required
                                    class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-gray-800 file:text-white dark:file:bg-gray-100 dark:file:text-gray-800" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <x-primary-button>{{ __('Adicionar') }}</x-primary-button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 text-sm rounded-lg p-4">
                    {{ __('Somente usuários com a role "fotografo" podem adicionar fotos. Sua conta atual não tem essa permissão.') }}
                </div>
            @endcan

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($cards->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">{{ __('Nenhuma foto cadastrada ainda.') }}</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($cards as $card)
                                <div class="relative rounded-lg overflow-hidden shadow border border-gray-200 dark:border-gray-700">
                                    <img src="{{ $card->image_url }}" alt="{{ $card->title ?? 'Foto' }}" class="w-full h-56 object-cover">
                                    

                                    <div class="p-3 bg-white dark:bg-gray-800 flex items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            @if ($card->title)
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $card->title }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $card->user->name }}</p>
                                        </div>
                                        @csrf
                                        <form method="POST" action="{{ route('cards.like' , $card->id) }} ">
                                            @csrf
                                            <button
                                                type="submit"
                                                name="bnt-like"
                                                class="group inline-flex items-center justify-center w-10 h-10 rounded-full
                                                    border border-gray-200 dark:border-gray-700
                                                    bg-white dark:bg-gray-800
                                                    hover:bg-red-50 dark:hover:bg-red-900/20
                                                    hover:border-red-300 dark:hover:border-red-700
                                                    transition-colors duration-150"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors duration-150 {{ $card->liked ?? false ? 'fill-red-500 text-red-500' : 'fill-none' }}"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 21s-6.716-4.35-9.428-8.06C.29 9.94 1.02 6.2 4.11 4.94c2.14-.87 4.42-.02 5.89 1.86 1.47-1.88 3.75-2.73 5.89-1.86 3.09 1.26 3.82 5 1.54 7.99C18.716 16.65 12 21 12 21z" />
                                                </svg>
                                            </button>
                                            <p>{{ $card->likes_count }}</p>
                                        </form> 
                                        @can('delete', $card)
                                            <form method="POST" action="{{ route('cards.destroy', $card) }}" onsubmit="return confirm('{{ __('Remover esta foto?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 whitespace-nowrap">
                                                    {{ __('Remover') }}
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $cards->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
