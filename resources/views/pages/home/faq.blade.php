<div id="faq" class="bg-white bg-opacity-60 px-4 pb-8 md:py-16">
    <div class="mx-auto w-full max-w-content">
        <h3
            class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
        >
            {{__('od.FAQ')}}
        </h3>

        <div class="mt-6 md:mt-[2.125rem]">
            <div
                class="flex flex-col gap-3 md:grid md:grid-cols-[1fr_2fr] md:gap-[0_1.5rem]"
            >

                @foreach($faqCategories as $index => $faqCategory)
                    @translate($faqCategory)
                    <button
                        class="section x-[1.875rem] col-start-1 h-[3.375rem] w-full rounded-lg bg-white text-center text-[1rem] font-semibold transition-all hover:text-blue hover:opacity-95 data-[active='true']:text-blue md:mb-3 md:h-[3.75rem] md:px-8 md:text-left md:text-[1.25rem] [&[data-active='true']_+_div]:block [&_+_div]:hidden"
                        {!! $index == 0 ? ' data-active="true"': ''!!}
                    >
                        {{$faqCategory->name}}
                    </button>

                    <div class="col-start-2 row-start-1 [grid-row:_1_/_1000]">
                        <ul class="flex flex-col gap-6 pt-1">
                            @foreach($faqCategory->faqs as $faq)
                                @translate($faq)
                                <li class="group">
                                    <button
                                        class="text-left text-[1rem] font-semibold leading-[1.375rem] text-mid-gray transition-colors hover:text-blue group-data-[active='true']:text-blue md:text-[1.5rem] md:font-medium md:leading-[2.25rem]"
                                    >
                                        {{$faq->question}}
                                    </button>

                                    <div
                                        class="max-w-[40.875rem] overflow-clip transition-all duration-300 ease-in-out md:mt-2"
                                        style="height: 0; opacity: 0"
                                    >
                                        <div
                                            class="pt-[1.25rem] text-[0.875rem] font-medium leading-6 md:pb-6 md:text-[1rem] md:leading-7 ck-content"
                                        >
                                            {!! $faq->answer !!}

                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
