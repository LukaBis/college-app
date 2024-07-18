<x-filament-widgets::widget>
    <x-filament::section>
        <form action="/export-csv/{{ $record->id }}" method="get">
            <button type="submit" style="
                    background-color: #1e1e1e;
                    color: #f5f5f5;
                    border: 1px solid #444;
                    padding: 10px 20px;
                    font-size: 14px;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s ease, color 0.3s ease;
                    font-weight: bold;
                ">
                Export valuations
            </button>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
