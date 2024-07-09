<div class="bg-white bg-opacity-60 px-4 pb-8 pt-8 md:py-16 md:pt-16">
    <div class="mx-auto w-full max-w-content">
        <h3
            class="text-[1.25rem] font-semibold leading-[1.75rem] md:text-[1.5rem] md:font-bold md:leading-[2.25rem]"
        >
            {{__('Partners')}}
        </h3>

        <div class="mt-6 md:mt-[2.125rem]">
            <ul class="grid gap-6 sm:grid-cols-3">
                @foreach(array_splice($partners, 0, 3) as $partner)
                    @php
                        $image = \App\Models\Attachment::find($partner['image_id']);
                    @endphp
                    @if($image && $image->isImage())
                        <li>
                            <a
                                href="{{$partner['url']}}"
                                title="{{$partner['title']}}"
                                class="block aspect-video rounded-[0.875rem] bg-[image:var(--background)] bg-cover bg-center transition-all md:grayscale md:hover:grayscale-0"
                                style="--background: url({{$image->downloadUrl}});"
                            ></a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>

