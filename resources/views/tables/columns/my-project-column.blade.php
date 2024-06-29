<div>
    @if(auth()->user()->projects()->get()->pluck('id')->contains($getState()))
        <b>My Project</b>
    @endif
</div>
