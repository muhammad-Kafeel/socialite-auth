<h1>Dashboard</h1>

@if($hasRole)
    <h2>Hello {{ $role }} 👋</h2>
    <p>Welcome, {{ $user->name }}</p>
@else
    <h2>You do NOT have the {{ $role }} role</h2>
@endif
