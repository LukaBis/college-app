<x-filament-widgets::widget>
    <x-filament::section>
        <h3 style="margin-bottom: 2rem;">
            Activation Records
        </h3>
        <ul>
            @foreach($records as $date => $action)
                <li style="margin-bottom: 10px;">
                    @if($action === 'activated')
                        <span style="color: lightgreen; margin-right: 10px;">{{ $action }}:</span> {{ $date }}
                    @elseif($action === 'deactivated')
                        <span style="color: #f14343; margin-right: 10px;">{{ $action }}:</span> {{ $date }}
                    @else
                        <span style="margin-right: 10px;">{{ $action }}:</span> {{ $date }}
                    @endif

                </li>
            @endforeach
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>
