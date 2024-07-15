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

        @if($webinars)
        <div id="news" class="glide px-0 pb-8 sm:px-0 md:py-16" data-gap="24">
            <div class="px-4 sm:px-16">
                <div class="mx-auto flex w-full max-w-content items-center gap-8">
                    <h3
                        class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
                    >
                        {{__('Recorded live streams of past webinars')}}
                    </h3>

                    <div
                        class="ml-auto flex items-center gap-2 fill-black md:ml-0"
                        data-glide-el="controls"
                    >
                        <button
                            class="glide__arrow glide__arrow--left flex h-6 w-6 rotate-180 items-center justify-center transition-colors hover:fill-blue"
                            data-glide-dir="<"
                        >
                            <svg
                                width="17"
                                height="12"
                                viewBox="0 0 17 12"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M16.5303 6.36822C16.8232 6.07533 16.8232 5.60045 16.5303 5.30756L11.7574 0.53459C11.4645 0.241696 10.9896 0.241696 10.6967 0.53459C10.4038 0.827483 10.4038 1.30236 10.6967 1.59525L14.9393 5.83789L10.6967 10.0805C10.4038 10.3734 10.4038 10.8483 10.6967 11.1412C10.9896 11.4341 11.4645 11.4341 11.7574 11.1412L16.5303 6.36822ZM0 6.58789H16V5.08789H0V6.58789Z"
                                ></path>
                            </svg>
                        </button>

                        <button
                            class="glide__arrow glide__arrow--right flex h-6 w-6 items-center justify-center transition-all hover:fill-blue"
                            data-glide-dir=">"
                        >
                            <svg
                                width="17"
                                height="12"
                                viewBox="0 0 17 12"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M16.5303 6.36822C16.8232 6.07533 16.8232 5.60045 16.5303 5.30756L11.7574 0.53459C11.4645 0.241696 10.9896 0.241696 10.6967 0.53459C10.4038 0.827483 10.4038 1.30236 10.6967 1.59525L14.9393 5.83789L10.6967 10.0805C10.4038 10.3734 10.4038 10.8483 10.6967 11.1412C10.9896 11.4341 11.4645 11.4341 11.7574 11.1412L16.5303 6.36822ZM0 6.58789H16V5.08789H0V6.58789Z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="glide__bullets mt-4 flex gap-4 px-4 md:hidden"
                data-glide-el="controls[nav]"
            >
                <button
                    class="glide__bullet h-[0.125rem] grow basis-0 rounded-full bg-[#D8D8D8]"
                    data-glide-dir="=0"
                ></button>
                <button
                    class="glide__bullet h-[0.125rem] grow basis-0 rounded-full bg-[#D8D8D8]"
                    data-glide-dir="=1"
                ></button>
                <button
                    class="glide__bullet h-[0.125rem] grow basis-0 rounded-full bg-[#D8D8D8]"
                    data-glide-dir="=2"
                ></button>
            </div>

            <div
                class="glide__track mt-6 overflow-clip md:mt-[2.125rem]"
                data-glide-el="track"
            >
                <ul class="glide__slides flex md:pl-[calc((100vw_-_81.5rem)_/_2)]">
                    @foreach($webinars as $webinar)
                    <li
                        class="glide__slide w-[calc(100vw_-_2rem)] shrink-0 select-none px-4 md:w-[33rem] md:px-0"
                    >
                        <div
                            class="flex flex-col gap-4 rounded-[1rem] bg-white p-6 md:gap-6 md:p-8"
                        >
                            <div class="overflow-clip rounded-[0.5rem]">
                                <iframe
                                    style="width: 100%"
                                    height="315"
                                    src="https://www.youtube.com/embed/{{$webinar['youtube']}}?controls=0"
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                ></iframe>
                            </div>

                            <div class="text-[1.125rem] font-bold leading-[1.625rem]">
                                {{$webinar['title']}}
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </main>

</x-public-layout>
