<div class="bg-white bg-opacity-60 px-4 pt-8 md:pb-[5.5rem]">
    <div class="mx-auto flex w-full max-w-content flex-col gap-8">
        <h3
            class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
        >
            {{__('Master\'s study programs defined for the Subject')}}
        </h3>

        <div
            id="programs"
            class="flex flex-col gap-[0.375rem] md:grid md:grid-cols-[1fr_2fr] md:gap-[0_1.5rem]"
        >

            @foreach($studyPrograms as $program)
                @translate($program)
                <button
                    data-active="true"
                    class="section col-start-1 w-full rounded-lg bg-white py-4 text-center text-[1rem] font-semibold transition-all hover:text-pink hover:opacity-95 data-[active='true']:text-pink md:mb-3 md:px-8 md:text-left md:text-[1.25rem] [&_+_div]:h-0"
                >
                    {{$program->name}}
                </button>

                <div
                    class="col-start-2 row-start-1 overflow-clip transition-all [grid-row:_1_/_1000]"
                >
                    <ul class="flex flex-col gap-6">
                        @foreach($program->universities as $university)
                            @translate($university)
                            <li>
                                <a
                                    href="{{ $university->url }}"
                                    class="flex items-center gap-2 py-4 text-mid-gray md:gap-5 md:p-4 md:hover:text-pink"
                                    target="_blank"
                                >
                                    <div class="h-[5.25rem] w-[5.25rem] shrink-0">
                                        <img src="{{ $university->logo->download_url }}" class="max-h-full max-w-full" />
                                    </div>

                                    <div
                                        class="text-[1rem] font-semibold leading-[1.375rem] md:text-[1.5rem] md:font-medium md:leading-9"
                                    >
                                        {{$university->name}}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

        </div>
    </div>
</div>

<script>
    const programs = document.getElementById("programs")
    const sections = programs?.querySelectorAll(".section")

    function initPrograms() {
        sections?.forEach((section, section_idx) => {
            if (section.dataset.active === "true") {
                openSection(section_idx)
            }

            section.addEventListener("click", () => {
                openSection(section_idx)
            })
        })
    }

    function openSection(idx) {
        sections?.forEach((section, section_idx) => {
            const wrapper = section.nextElementSibling
            const text = wrapper?.querySelector("ul")

            if (section_idx === idx) {
                section.dataset.active = "true"
                wrapper.style.height = text.clientHeight + "px"
                wrapper.style.opacity = "1"
            } else {
                delete section.dataset.active
                wrapper.style.height = "0"
                wrapper.style.opacity = "0"
            }
        })
    }

    initPrograms()
</script>
