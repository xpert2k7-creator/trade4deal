@props(['action', 'name'])

<form method="POST" action="{{ $action }}" class="d-inline"
      onsubmit="return confirm('Remove login for {{ $name }}? They will no longer be able to sign in.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
</form>
