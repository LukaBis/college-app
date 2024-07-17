<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            .custom-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                font-size: 18px;
                text-align: left;
                background-color: #2e2e2e;
                color: #ddd;
            }
            .custom-table th, .custom-table td {
                padding: 12px 15px;
            }
            .custom-table th {
                background-color: #3b3b3b;
                color: #ddd;
                border-bottom: 2px solid #444;
            }
            .custom-table tr:nth-child(even) {
                background-color: #383838;
            }
            .custom-table tr:hover {
                background-color: #4a4a4a;
            }
            .custom-table td {
                border-bottom: 1px solid #444;
            }
            .custom-table h3 {
                margin: 0;
                color: #bbb;
            }
            .scrollable-table {
                overflow-x: auto; /* Enables horizontal scrolling */
                margin: 0 -20px; /* Adjust margins if needed */
            }
        </style>

        <div class="scrollable-table">
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
                    @foreach($record->valuationTerms as $valuationTerm)
                        <th>
                            <h3>{{ $valuationTerm->title.' (negative points)'  }}</h3>
                        </th>
                    @endforeach
                    @foreach($record->customDeadlines as $customDeadline)
                        <th>
                            <h3>{{ $customDeadline->title.' (negative points)'  }}</h3>
                        </th>
                    @endforeach
                    <th>
                        <h3>Final</h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                    @if(auth()->user()->hasRole('Student'))
                        <tr>
                            <td>
                                {{ auth()->user()->full_name }}
                            </td>
                            @foreach($record->valuationTerms as $valuationTerm)
                                <td>
                                    {{ auth()->user()->finalValuationTermPoints($valuationTerm) }}
                                </td>
                            @endforeach
                            <td>
                                {{ auth()->user()->averageValuationTermPoints($record) }}
                            </td>
                            @foreach($record->valuationTerms as $valuationTerm)
                                <td>
                                    {{ auth()->user()->valuationTermNegativePoints($valuationTerm, $record) }}
                                </td>
                            @endforeach
                            @foreach($record->customDeadlines as $customDeadline)
                                <td>
                                    {{ auth()->user()->negativePointsFromCustomDeadline($customDeadline) }}
                                </td>
                            @endforeach
                            <td>
                                {{ auth()->user()->getFinalPointsOfAllValuationTerms($record) }}
                            </td>
                        </tr>
                    @else
                        @foreach($record->students as $student)
                            <tr
                                @if(! $student->active)
                                    style="color: red;"
                                @endif
                            >
                                <td>
                                    {{ $student->full_name }}
                                </td>
                                @foreach($record->valuationTerms as $valuationTerm)
                                    <td>
                                        {{ $student->finalValuationTermPoints($valuationTerm) }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ $student->averageValuationTermPoints($record) }}
                                </td>
                                @foreach($record->valuationTerms as $valuationTerm)
                                    <td>
                                        {{ $student->valuationTermNegativePoints($valuationTerm, $record) }}
                                    </td>
                                @endforeach
                                @foreach($record->customDeadlines as $customDeadline)
                                    <td>
                                        {{ $student->negativePointsFromCustomDeadline($customDeadline) }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ $student->getFinalPointsOfAllValuationTerms($record) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
