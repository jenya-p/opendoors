<x-public-layout>
    <main class="grow">
        <div
            class="glide px-0 pb-8 pt-6 sm:px-0 md:py-16 md:pt-12"
            data-per-view="3.5"
            data-gap="24"
        >
            <div class="px-4">
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
                            <span class="text-black">{{__('Rules')}}</span>
                        </li>
                    </ul>

                    <div class="mt-6 flex items-center gap-8">
                        <h3
                            class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
                        >
                            {{$title}}
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
                <button
                    class="glide__bullet h-[0.125rem] grow basis-0 rounded-full bg-[#D8D8D8]"
                    data-glide-dir="=3"
                ></button>
            </div>

            <div
                class="glide__track mt-6 overflow-clip md:mt-[2.125rem]"
                data-glide-el="track"
            >
                <ul class="glide__slides flex md:pl-[calc((100vw_-_81.5rem)_/_2)]">
                    @foreach($blocks as $block)
                        @php
                            $file = \App\Models\Attachment::find($block['file_id']);
                        @endphp
                        @if($file)
                            <li
                                class="glide__slide w-[calc(100vw_-_2rem)] shrink-0 px-4 md:w-[18rem] md:px-0"
                            >
                                <a
                                    href="{{$file->downloadUrl}}"
                                    title="Download {{$block['title']}}"
                                    download
                                    class="flex h-full flex-col gap-4 rounded-[1rem] bg-white fill-gray-light p-6 transition-colors md:hover:fill-pink md:hover:text-pink"
                                >
                                    <div class="text-[1.5rem] font-bold leading-9">
                                        {{$block['title']}}
                                    </div>

                                    <div class="grow text-[1rem] font-medium leading-6">
                                        {{$block['summary']}}
                                    </div>

                                    <div class="h-[1.5rem] w-[1.25rem] shrink-0">
                                        <svg
                                            width="19"
                                            height="24"
                                            viewBox="0 0 19 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.4371 0C9.1 0 8.82419 0.24 8.82419 0.533333V17.0667L3.83516 13.4667C3.57774 13.2747 3.19161 13.296 2.97097 13.5147C2.75032 13.7387 2.77484 14.0747 3.02613 14.2667L9.03258 18.64C9.13677 18.72 9.27774 18.7733 9.42484 18.7733C9.42484 18.7733 9.42484 18.7733 9.43097 18.7733H9.4371C9.59032 18.7733 9.72516 18.72 9.82935 18.64L15.8358 14.2667C16.0932 14.0747 16.1177 13.7333 15.891 13.5147C15.6703 13.2907 15.2781 13.2693 15.0268 13.4667L10.0377 17.0667V0.533333C10.0377 0.24 9.76193 0 9.42484 0H9.4371Z"
                                            ></path>
                                            <path
                                                d="M0 23.36C0 23.6534 0.275806 23.8934 0.612903 23.8934H18.2645C18.6016 23.8934 18.8774 23.6534 18.8774 23.36C18.8774 23.0667 18.6016 22.8267 18.2645 22.8267H0.612903C0.275806 22.8267 0 23.0667 0 23.36Z"
                                            ></path>
                                        </svg>
                                    </div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </main>
</x-public-layout>
