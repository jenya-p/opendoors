<x-public-layout>
    <main class="grow">
        <div class="bg-white bg-opacity-60 px-4 pb-8 pt-6 md:py-12">
            <div class="mx-auto w-full max-w-content">
                <ul
                    class="flex flex-wrap gap-x-4 gap-y-2 text-[0.75rem] font-semibold uppercase text-mid-gray"
                >
                    <li
                        class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
                    >
                        <a href="{{ route('public.home') }}" title="{{__('Home')}}"
                           class="text-mid-gray hover:text-blue">{{__('Home')}}</a>
                    </li>

                    <li
                        class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
                    >
                        <span class="text-black">{{ $h1  }}</span>
                    </li>
                </ul>

                <div class="mt-6 flex max-w-[52.5rem] flex-col">
                    <h1 class="text-[1.5rem] font-bold leading-9">{{ $h1  }}</h1>

                    <div
                        class="[&#38;>_*]:my-4 [&#38;_:is(ol,ul)]:pl-4 [&#38;_ol]:list-decimal [&#38;_ul]:list-disc [&#38;_a]:underline [&#38;_a]:text-blue [&#38;_a:hover]:no-underline [&#38;_h4]:text-[1.125rem] [&#38;_h5]:text-[1rem] [&#38;_h3]:text-[1.25rem] [&#38;_h2]:text-[1.375rem] [&#38;_h1]:text-[1.5rem] [&#38;_:is(h1,h2,h3,h4,h5)]:leading-9 [&#38;_:is(h1,h2,h3,h4,h5)]:font-bold leading-7 after:clear-both after:block"
                    >
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>


    </main>

</x-public-layout>
