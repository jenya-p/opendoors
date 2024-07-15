@php
    $materials = $profile->getFilesOfType(\App\Models\ProfileFileType::TYPE_RESULTS);
@endphp
@if(count($materials) > 0)
<div class="mt-6">
    <ul
        class="grid grid-cols-[repeat(auto-fit,_minmax(13.4375rem,_auto))] gap-4 md:gap-5"
    >
        @foreach($materials as $material)
            @translate($material)
            @translate($material->type)
            @if($material->file)
        <li>
            <a
                href="{{$material->file->downloadUrl}}"
                class="group flex h-full flex-row-reverse items-center gap-2 rounded-[1rem] border border-pink fill-pink p-4 transition-colors md:min-h-[13.4375rem] md:flex-col md:items-start md:gap-4 md:px-8 md:py-6 md:hover:bg-pink md:hover:fill-white"
            >
                <div
                    class="grow text-balance text-[1rem] font-semibold leading-[1.375rm] text-pink transition-colors md:text-[1.25rem] md:leading-7 md:group-hover:text-white"
                >
                    {{$material->type->name}}
                </div>
                @if(count($material->type->tracks))
                    @translate($material->type->tracks)
                    <p class="text-pink md:group-hover:text-white">{{$material->type->tracks->map(fn($itm) => $itm->name)->join(', ')}}</p>
                @endif

                @if($material->type->summary)
                    <div class="grow text-[1rem] font-medium leading-1 md:group-hover:text-white">
                        {!! $material->type->summary !!}
                    </div>
                @endif

                <div class="h-[1.5rem] w-[1.25rem] shrink-0">
                    <svg
                        width="19"
                        height="25"
                        viewBox="0 0 19 25"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M9.4371 0.0500488C9.1 0.0500488 8.82419 0.290049 8.82419 0.583382V17.1167L3.83516 13.5167C3.57774 13.3247 3.19161 13.346 2.97097 13.5647C2.75032 13.7887 2.77484 14.1247 3.02613 14.3167L9.03258 18.69C9.13677 18.77 9.27774 18.8234 9.42484 18.8234C9.42484 18.8234 9.42484 18.8234 9.43097 18.8234H9.4371C9.59032 18.8234 9.72516 18.77 9.82935 18.69L15.8358 14.3167C16.0932 14.1247 16.1177 13.7834 15.891 13.5647C15.6703 13.3407 15.2781 13.3194 15.0268 13.5167L10.0377 17.1167V0.583382C10.0377 0.290049 9.76193 0.0500488 9.42484 0.0500488H9.4371Z"
                        ></path>
                        <path
                            d="M0 23.4101C0 23.7034 0.275806 23.9434 0.612903 23.9434H18.2645C18.6016 23.9434 18.8774 23.7034 18.8774 23.4101C18.8774 23.1168 18.6016 22.8768 18.2645 22.8768H0.612903C0.275806 22.8768 0 23.1168 0 23.4101Z"
                        ></path>
                    </svg>
                </div>
            </a>
        </li>
            @endif
        @endforeach
    </ul>
</div>
@endif
