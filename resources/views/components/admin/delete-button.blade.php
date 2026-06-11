@props(['action', 'label' => 'Delete this item?'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $label }} This cannot be undone.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="chip border border-rose-200 bg-white text-rose-600 hover:bg-rose-50">Delete</button>
</form>
