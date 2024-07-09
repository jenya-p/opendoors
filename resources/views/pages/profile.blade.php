@translate($profile)
<x-public-layout>
    <main class="grow">
        <div class="px-4 pt-6 md:pt-16">
            <div class="mx-auto w-full max-w-content">
                <div class="flex items-center gap-4">
                    <?php include(resource_path('images/profiles/' . $profile->icon)) ?>

                    <div
                        class="flex flex-col gap-4 text-[0.875rem] font-medium leading-5 md:gap-2 md:text-[1.25rem] md:font-semibold md:leading-7"
                    >
                        <h1
                            class="text-[1.5rem] font-bold leading-9 md:text-[2.5rem] md:leading-[2.5rem]"
                        >
                            {{$profile->name}}
                        </h1>
                        @if($profile->coordinator)
                            @translate($profile->coordinator)
                        <p>{{__('Coordinator')}} — {{$profile->coordinator->name}}</p>
                        @endif
                    </div>
                </div>

                @include('pages.profile.files')

            </div>
        </div>

        @include('pages.profile.downloads')

        @include('pages.profile.study-programs')

        @include('pages.home.profiles')

</main>

</x-public-layout>
