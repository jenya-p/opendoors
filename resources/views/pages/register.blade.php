<x-public-layout>
    <main class="grow">
        <div
            class="px-0 pb-8 pt-6 sm:px-0 md:py-16 md:pt-12"
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
                            <span class="text-black">{{__('Registration')}}</span>
                        </li>
                    </ul>
                    <div class="mt-6 ">
                        <div id="registration_form"
                             edu_level_options="{{ strip_tags(json_encode($edu_level_options)) }}"
                             citizenship_options="{{ json_encode($citizenship_options) }}"
                             locale_options="{{ strip_tags(json_encode($locale_options)) }}"
                             sex_options="{{ strip_tags(json_encode($sex_options)) }}"
                             track_options="{{ strip_tags(json_encode($track_options)) }}"
                             profile_options="{{ strip_tags(json_encode($profile_options)) }}"
                        />
                    </div>
                </div>
            </div>

        </div>
    </main>
    @vite(['resources/js/register.js', 'resources/css/guest-layout.scss'])
</x-public-layout>
