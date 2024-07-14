<div id="news" class="glide px-0 pb-8 sm:px-0 md:py-16" data-gap="24">
    <div class="px-4 sm:px-16">
        <div class="mx-auto flex w-full max-w-content items-center gap-8">
            <h3 class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]">
                {{ __($title) }}
            </h3>

            <div class="ml-auto flex items-center gap-2 fill-black md:ml-0" data-glide-el="controls">
                <button class="glide__arrow glide__arrow--left flex h-6 w-6 rotate-180 items-center justify-center transition-colors hover:fill-blue"
                    data-glide-dir="<">
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
            @foreach($items as $item)
            @php $item->translate() @endphp
            <li
                class="glide__slide w-[calc(100vw_-_2rem)] shrink-0 px-4 md:w-[33rem] md:px-0"
            >
                <div
                    title="{{$item->title}}"
                    class="group flex h-full select-none flex-col gap-8 rounded-2xl bg-white fill-gray-light p-8 transition-all hover:fill-blue md:gap-11"
                >
                    <div class="flex grow flex-col gap-4 md:gap-[1.125rem]">
                        <a href="{!! route('public.news.show', $item) !!}" class="flex flex-col gap-4">
                            <time
                                datetime="2017-02-14"
                                class="text-[0.75rem] font-medium leading-7 text-mid-gray md:text-[1rem]"
                            >
                                {{$item->date->translatedFormat('j F Y')}}
                            </time>

                            <h2
                                class="text-[1.125rem] font-bold leading-[1.625rem] transition-colors group-hover:text-blue md:text-[1.5rem] md:leading-9"
                            >
                                {{$item->title}}
                            </h2>
                        </a>

                        <div

                            class="text-[0.875rem] font-medium leading-[1.5rem] md:text-[1rem] md:leading-7 "
                        >
                            {!! $item->summary !!}
                        </div>
                    </div>

                    <a href=""{!! route('public.news.show', $item) !!}">
                    <svg
                            width="41"
                            height="10"
                            viewBox="0 0 41 10"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M40.4666 5.0627C40.7016 4.82771 40.7016 4.44671 40.4666 4.21171L36.6371 0.382252C36.4022 0.147257 36.0212 0.147257 35.7862 0.382252C35.5512 0.617247 35.5512 0.998248 35.7862 1.23324L39.1901 4.63721L35.7862 8.04117C35.5512 8.27617 35.5512 8.65717 35.7862 8.89216C36.0212 9.12716 36.4022 9.12716 36.6371 8.89216L40.4666 5.0627ZM0.326172 5.23895H40.0411V4.03547H0.326172V5.23895Z"
                            ></path>
                        </svg>
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
