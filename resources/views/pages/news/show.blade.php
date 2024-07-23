<x-public-layout>
@php $item->translate() @endphp
<main class="grow">
    <div class="bg-white bg-opacity-60 px-4 pb-8 pt-6 md:py-12">
        <div class="mx-auto w-full max-w-content">
            <ul
                class="flex flex-wrap gap-x-4 gap-y-2 text-[0.75rem] font-semibold uppercase text-mid-gray"
            >
                <li
                    class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
                >
                    <a href="{{ route('public.home') }}" title="{{__('Home')}}" class="text-mid-gray hover:text-blue"
                    >{{ __('Home') }}</a
                    >
                </li>

                <li
                    class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
                >
                    <a href="{{ route('public.news.index') }}" title="{{ __('News') }}" class="text-mid-gray hover:text-blue">
                        {{ __('News') }}
                    </a>
                </li>
                <li
                    class="relative [&:not(:last-child)]:pr-6 [&:not(:last-child)]:after:absolute [&:not(:last-child)]:after:right-0 [&:not(:last-child)]:after:top-1/2 [&:not(:last-child)]:after:-translate-y-1/2 [&:not(:last-child)]:after:content-['/']"
                >
              <span class="text-black"
              >{{$item->title}}</span
              >
                </li>
            </ul>

            <div class="mt-6 flex max-w-[52.5rem] flex-col">
                <div class="flex flex-col gap-4">
                    <time
                        datetime="2017-02-14"
                        class="text-[0.75rem] font-medium leading-7 text-mid-gray md:text-[1rem]"
                    >
                        {{$item->date->translatedFormat("j F Y")}}
                    </time>

                    <h1 class="text-[1.5rem] font-bold leading-9">
                        {{$item->title}}
                    </h1>
                </div>

                <div
                    class="[&#38;>_*]:my-4 [&#38;_:is(ol,ul)]:pl-4 [&#38;_ol]:list-decimal [&#38;_ul]:list-disc [&#38;_a]:underline [&#38;_a]:text-blue [&#38;_a:hover]:no-underline [&#38;_h4]:text-[1.125rem] [&#38;_h5]:text-[1rem] [&#38;_h3]:text-[1.25rem] [&#38;_h2]:text-[1.375rem] [&#38;_h1]:text-[1.5rem] [&#38;_:is(h1,h2,h3,h4,h5)]:leading-9 [&#38;_:is(h1,h2,h3,h4,h5)]:font-bold leading-7 after:clear-both after:block ck-content"
                >
                    {!! $item->content !!}
                </div>
            </div>
        </div>
    </div>

    <x-latest-news title="All News"/>
</main>


</x-public-layout>
