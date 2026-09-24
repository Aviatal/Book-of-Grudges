@if (session('status'))
    <div class="mb-5 px-4 py-3 text-sm" style="border: 1px solid var(--border-accent); background: var(--bg-inset-alt); color: var(--gold-bright)">
        {{ session('status') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-5 px-4 py-3 text-sm" style="border: 1px solid var(--danger-border); background: var(--danger-bg); color: var(--danger-text)">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
