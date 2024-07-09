<x-public-layout>
    @include('pages.home.top', $top)
    <main class="mt-8 grow md:mt-0">
        <x-latest-news title="Latest News"/>
        @include('pages.home.profiles')
        @include('pages.home.schedule')
        @include('pages.home.universities')
        @include('pages.home.partners')
        @include('pages.home.faq')
    </main>
</x-public-layout>
