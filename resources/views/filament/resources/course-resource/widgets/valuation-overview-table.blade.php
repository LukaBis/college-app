<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            .custom-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                font-size: 18px;
                text-align: left;
                background-color: #2e2e2e; /* Dark background */
                color: #ddd; /* Light text color */
            }
            .custom-table th, .custom-table td {
                padding: 12px 15px;
            }
            .custom-table th {
                background-color: #3b3b3b; /* Slightly lighter dark background */
                color: #ddd; /* Light text color */
                border-bottom: 2px solid #444; /* Dark border */
            }
            .custom-table tr:nth-child(even) {
                background-color: #383838; /* Alternating row color */
            }
            .custom-table tr:hover {
                background-color: #4a4a4a; /* Hover effect */
            }
            .custom-table td {
                border-bottom: 1px solid #444; /* Dark border */
            }
            .custom-table h3 {
                margin: 0;
                color: #bbb; /* Slightly darker text color for headers */
            }
        </style>

        <table class="custom-table">
            <thead>
            <tr>
                <th>
                    <h3>Full name</h3>
                </th>
                @foreach($record->valuationTerms as $valuationTerm)
                    <th>
                        <h3>{{ $valuationTerm->title.' ('.$valuationTerm->term.')'  }}</h3>
                    </th>
                @endforeach
                <th>
                    <h3>Average</h3>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($record->students as $student)
                <tr>
                    <td>
                        {{ $student->full_name }}
                    </td>
                    @foreach($record->valuationTerms as $valuationTerm)
                        <td>
                            {{ $student->finalValuationTermPoints($valuationTerm) }}
                        </td>
                    @endforeach
                    <td>
                        {{ $student->getFinalPointsOfAllValuationTerms($record) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-filament::section>
</x-filament-widgets::widget>
