<div class="list-group-item">
  <img class="me-3" src="{{ $user->gravatar() }}" alt="{{ $user->name }}" width=32>
  <a href="{{ route('users.show', $user) }}" style="text-decoration: none">
    {{ $user->name }}
  </a>
</div>
