@props(['name', 'class' => ''])

<svg xmlns="http://www.w3.org/2000/svg"
     class="feather feather-{{ $name }} {{ $class }}"
     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    @switch($name)
        @case('home')
            <path d="M3 12l2-2m0 0l7-7 7 7m-9 2v8h4v-8m5 0l2 2M5 10l2 2"></path>
            @break
        @case('file-plus')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <path d="M14 2v6h6"/>
            <path d="M12 11v6m3-3H9"/>
            @break
        @case('truck')
            <path d="M3 3h13v13H3z"/>
            <path d="M16 8h4l1 3v5h-6V8z"/>
            @break
        @case('file-text')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <path d="M14 2v6h6"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            @break
        @case('clipboard')
            <path d="M9 2H7a2 2 0 0 0-2 2v1h10V4a2 2 0 0 0-2-2H9z"/>
            <path d="M17 4v16a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4"/>
            @break
        @case('calendar')
            <path d="M8 7V3M16 7V3M3 11h18M5 20h14a2 2 0 0 0 2-2V7H3v11a2 2 0 0 0 2 2z"/>
            @break
        @default
            <circle cx="12" cy="12" r="10"/>
    @endswitch
</svg>
