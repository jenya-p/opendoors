<div
    id="subjects"
    class="px-4 pb-8 pt-0 md:bg-white md:bg-opacity-60 md:py-16 md:pt-16"
>
    <div class="mx-auto w-full max-w-content">
        <h3
            class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
        >
            {{__('Subjects of the OpenDoors')}}
        </h3>

        <div class="mt-6 md:mt-[2.125rem]">
            <ul
                class="columns-2 gap-3 md:grid md:grid-cols-[repeat(auto-fit,_minmax(13.375rem,_auto))] md:gap-6"
            >
                @foreach($profiles as $profile)
                    @translate($profile)
                    <li class="mb-3 inline-block w-full md:mb-0 md:block">
                        <a
                            href="{{ route('public.profile.show', $profile) }}"
                            title=""
                            class="group flex h-full flex-col gap-6 rounded-xl bg-white fill-gray-light px-6 py-4 transition-colors hover:bg-opacity-95 hover:fill-blue md:px-[2.375rem] md:pb-10 md:pt-7"
                        >
                            <div class="flex grow flex-col gap-4 md:gap-3">
                                <div
                                    class="h-16 w-16 shrink-0 [&_svg]:h-auto [&_svg]:max-w-full [&_svg]:object-contain">
                                        <?php include(resource_path('images/profiles/' . $profile->icon)) ?>
                                </div>

                                <div
                                    class="text-balance text-[1rem] font-semibold leading-[1.375rem] transition-colors group-hover:text-blue md:text-[1.25rem] md:leading-7"
                                >
                                    {{$profile->name}}
                                </div>
                            </div>

                            <div class="hidden md:block">
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
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
