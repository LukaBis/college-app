<x-filament-widgets::widget>
    <x-filament::section>
        @if($projectSelected)
            <h3>
                <b>Your selected project</b>
            </h3>
        @elseif($displayWarning)
            <p>
                You have already chosen another project for this course.
            </p>
        @else
            <form action='{{ "/projects/{$projectId}/users/{$studentId}"  }}' method="post">
                @csrf
                <input type="hidden" name="studentId" value="{{ $studentId  }}">
                <input type="hidden" name="projectId" value="{{ $projectId  }}">
                <button style="background-color: #3b82f6; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;"
                        onmouseover="this.style.backgroundColor='#1e40af'"
                        onmouseout="this.style.backgroundColor='#3b82f6'"
                        type="submit">
                    Assign me to this project
                </button>
            </form>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
