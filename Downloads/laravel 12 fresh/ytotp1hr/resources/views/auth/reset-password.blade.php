<h2>Reset Password</h2>

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li style="color: red;">{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('reset-password.submit') }}" method="POST">
    @csrf
    <input type="password" name="password" placeholder="New password" required>
    <br><br>
    <input type="password" name="password_confirmation" placeholder="Confirm password" required>
    <br><br>
    <button type="submit">Change Password</button>
</form>
