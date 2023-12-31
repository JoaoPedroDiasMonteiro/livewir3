<div class="px-4 sm:px-6 lg:px-8">
    <header class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">@lang('Posts')</h1>
            <p class="mt-2 text-sm text-gray-700">
                @lang('A list of all the Posts in your account including their id, title, and content')
            </p>
        </div>
        <p>search: '{{ $search }}'</p>
        <div class="mt-4 flex items-center gap-4 sm:ml-16 sm:mt-0 sm:flex-none">
            {{-- Not working --}}
            <livewire:search wire:model='search' />
            <input type="text" wire:model.live='search' class="h-8 rounded-lg px-5 focus:outline-indigo-500" placeholder="Search Something...">
            <x-button :label="__('Add post')" />
        </div>
    </header>

    <section class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <caption class="hidden">New York City Marathon Results 2013</caption>
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Title
                                    <button x-on:click="$wire.set('sort', 'title')">
                                        <>
                                    </button>
                                    {{ $sort }}
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Content
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="text-gradient sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($this->posts as $post)
                                <tr class="transition-all hover:bg-indigo-50" wire:key='post-{{ $post->id }}'>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        # {{ $post->id }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $post->title }}
                                    </td>
                                    <td class="truncate whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ str($post->content)->limit(100) }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-1 border-t">
                        {{ $this->posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <livewire:post.count :posts="$this->posts->getCollection()" />
    </footer>
</div>
