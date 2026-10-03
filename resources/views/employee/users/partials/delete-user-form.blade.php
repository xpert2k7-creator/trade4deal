@can('delete', $user)
    <form method="POST"
          action="{{ route('employee.users.destroy', $user) }}"
          class="{{ $class ?? 'd-inline' }}"
          onsubmit="return confirm('Permanently remove {{ addslashes($user->company_name ?: $user->name) }}? Their login will stop working and seller products will be hidden.');">
        @csrf
        @method('DELETE')
        @if (! empty($search))
            <input type="hidden" name="q" value="{{ $search }}">
        @endif
        <button type="submit" class="{{ $buttonClass ?? 'btn btn-sm btn-outline-danger' }}">
            <i class="bi bi-trash"></i> {{ $label ?? 'Delete' }}
        </button>
    </form>
@endcan
