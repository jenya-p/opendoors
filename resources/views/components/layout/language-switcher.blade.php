<div class="flex gap-2 uppercase lg:absolute lg:-top-8 lg:right-0">
    @if ($locale == 'ru')
        <a href="{{ route('set-locale', 'en') }}" title="{{__('od.English')}} " class="text-gray hover:text-blue"> {{__('od.En')}} </a>
        <span title="{{__('od.Русский')}} " class="text-blue"> {{__('od.Ру')}} </span>
    @else
        <span class="text-blue"> {{__('od.En')}} </span>
        <a href="{{ route('set-locale', 'ru') }}" title="{{__('od.Русский')}} " title="{{__('od.English')}} class="text-gray hover:text-blue"> {{__('od.Ру')}} </a>
    @endif

</div>
