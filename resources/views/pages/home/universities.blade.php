<div
    id="universities"
    class="glide mt-8 px-0 pb-8 sm:px-0 md:mt-0 md:py-16"
>
    <div class="px-4 sm:px-16">
        <div class="mx-auto flex w-full max-w-content items-center gap-8">
            <h3
                class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
            >
                {{__('Competition organized by leading Russian universities')}}
            </h3>
        </div>
    </div>

    <!-- Это только мобильный элемент для перехода между университетами -->
    <!-- поскольку в мобильной версии они в группах по 4 то кнопок должно быть -->
    <!-- в 4 раза меньше. ceil(universities_count / 4) -->
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
        <button
            class="glide__bullet h-[0.125rem] grow basis-0 rounded-full bg-[#D8D8D8]"
            data-glide-dir="=4"
        ></button>
        <button
            class="glide__bullet h-[0.125rem] grow basis-0 rounded-full bg-[#D8D8D8]"
            data-glide-dir="=5"
        ></button>
    </div>

    <div
        class="glide__track mt-4 overflow-clip md:hidden"
        data-glide-el="track"
    >
        <!-- Здесь университеты группируются по 4 для мобильной версии  -->
        <!-- сами элменты ссылки абслютно ничем от десктопной версии не отличаются -->
        <!-- посто их по 4 в каждом li -->
        <ul class="glide__slides flex">
            <li class="grid grid-cols-2 gap-2 px-2">
                @foreach($universities as $index => $university)
                    @translate($university)
                    @if($index != 1 && ($index - 1) % 4 == 0)
                        {!! '</li><li class="grid grid-cols-2 gap-2 px-2">' !!}}
                    @endif
                <a
                    href="{{ $university->url }}"
                    title="HSE University"
                    class="group flex min-h-[9.75rem] w-full flex-col gap-4 rounded-[1rem] bg-white p-4 transition-colors hover:bg-opacity-95 md:min-h-[19.5rem] md:p-8 md:px-[2.25rem]"
                >
                    <div
                        class="text-balance text-[0.5rem] font-medium leading-3 text-mid-gray transition-colors group-hover:text-blue md:text-[0.875rem] md:leading-6"
                    >
                        {{ $university->name }}
                    </div>

                    <div
                        class="flex grow bg-[image:var(--background)] bg-contain bg-center bg-no-repeat transition-all group-hover:opacity-100 group-hover:grayscale-0 md:opacity-80 md:grayscale"
                        style="
                    --background: url({{ $university->logo->downloadUrl }});
                  "
                    ></div>
                </a>
                @endforeach
            </li>
        </ul>
    </div>

    <!-- Десктопный блок с университетами -->
    <!-- элемент ссылки здесь дублирует ссылку мобильной версии -->
    <!-- но здесь на каждый li только одна ссылка -->
    <div class="mx-auto mt-[2.125rem] hidden w-full max-w-content md:block">
        <ul
            class="grid grid-cols-[repeat(auto-fit,_minmax(8.75rem,_auto))] gap-2 md:grid-cols-[repeat(auto-fit,_minmax(17.625rem,_auto))]"
        >
            @foreach($universities as $index => $university)
                @translate($university)
            <li>
                <a
                    target="_blank"
                    href="{{ $university->url }}"
                    title="HSE University"
                    class="group flex min-h-[9.75rem] w-full flex-col gap-4 rounded-[1rem] bg-white p-4 transition-colors hover:bg-opacity-95 md:min-h-[19.5rem] md:p-8 md:px-[2.25rem]"
                >
                    <div
                        class="text-balance text-[0.5rem] font-medium leading-3 text-mid-gray transition-colors group-hover:text-blue md:text-[0.875rem] md:leading-6"
                    >
                        {{ $university->name }}
                    </div>

                    <div
                        class="flex grow bg-[image:var(--background)] bg-contain bg-center bg-no-repeat transition-all group-hover:opacity-100 group-hover:grayscale-0 md:opacity-80 md:grayscale"
                        style="
                    --background: url({{ $university->logo->downloadUrl }});
                  "
                    ></div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
