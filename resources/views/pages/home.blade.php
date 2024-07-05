<x-public-layout>
    @include('pages.home.top')
    <main class="mt-8 grow md:mt-0">
        @include('pages.home.news')
        @include('pages.home.subjects')
        @include('pages.home.schedule')
        @include('pages.home.universities')
        @include('pages.home.partners')
        @include('pages.home.faq')
    </main>
</x-public-layout>
