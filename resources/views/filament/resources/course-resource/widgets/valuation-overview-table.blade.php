<x-filament-widgets::widget>
    <x-filament::section>
        <table>
            <tr>
                <td>
                    <h3>Full name</h3>
                </td>
                @foreach($record->valuationTerms as $valuationTerm)
                    <td>
                        <h3>{{ $valuationTerm->title.' ('.$valuationTerm->term.')'  }}</h3>
                    </td>
                @endforeach
                    <td>
                        <h3>Average</h3>
                    </td>
            </tr>

            @foreach($record->students as $student)
                <tr>
                    <td>
                        {{ $student->full_name }}
                    </td>
                    @foreach($record->valuationTerms as $valuationTerm)
                        <td>
                            {{ $student->finalValuationTermPoints($valuationTerm)  }}
                        </td>
                    @endforeach
                    <td>
                        {{ $student->getFinalPointsOfAllValuationTerms($record) }}
                    </td>
                </tr>
            @endforeach
        </table>
    </x-filament::section>
</x-filament-widgets::widget>
