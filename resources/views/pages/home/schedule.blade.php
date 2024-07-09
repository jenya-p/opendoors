<div class="glide bg-white px-0 pb-8 pt-8 md:py-16">
    <div class="px-4 sm:px-16">
        <div class="mx-auto flex w-full max-w-content items-center gap-8">
            <h3
                class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
            >
                {{__('od.Schedule')}}
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
        class="glide__track mt-6 overflow-clip md:mt-[2.125rem]"
        data-glide-el="track"
    >
        <ul
            id="schedule"
            class="glide__slides flex md:pl-[calc((100vw_-_81.5rem)_/_2)]"
        >
            @foreach($schedule as $item)
                @translate($item)
                <li
                    class="glide__slide group w-[30.625rem] shrink-0 select-none px-4 md:px-0"
                >
                    <div class="flex flex-col gap-3">
                        <div class="pr-4 font-medium leading-7 text-blue">
                            {{$item->date_range_description }}
                        </div>

                        <div
                            class="relative h-[0.625rem] before:absolute before:left-0 before:top-0 before:h-[0.625rem] before:w-[0.625rem] before:rounded-full before:bg-blue after:absolute after:left-0 after:top-1/2 after:w-[calc(100vw_+_0.5rem)] after:border-b after:border-blue md:after:w-full"
                        ></div>
                    </div>

                    <div class="mt-[1.125rem] flex flex-col gap-8 pr-6">
                        <h4 class="text-[1.125rem] font-bold leading-[1.625rem]">
                            {{$item->title}}
                        </h4>

                        <div class="text-[0.875rem] font-medium leading-6">
                            <p>
                                {{$item->summary}}
                            </p>
                        </div>

                        @if($item->details)
                            <button
                                data-set="true"
                                class="text-left text-[0.75rem] font-semibold uppercase text-pink transition-colors hover:text-black group-data-[active='true']:hidden"
                            >
                                {{__('od.Open detailed schedule')}}
                            </button>

                            <button
                                data-set="false"
                                class="hidden text-left text-[0.75rem] font-semibold uppercase text-gray transition-colors hover:text-black group-data-[active='true']:inline-block"
                            >
                                {{__('od.Close detailed schedule')}}
                            </button>
                        @endif
                    </div>

                    @if($item->details)
                        <div
                            class="text text-[0.875rem] font-medium leading-6 transition-all [&_ol]:my-2 [&_ol]:list-inside [&_ol]:list-decimal [&_p]:my-2 [&_ul]:my-2 [&_ul]:list-inside [&_ul]:list-disc"
                            style="height: 0; opacity: 0"
                        >
                            <div class="pt-6">
                                {!! $item->details!!}
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
