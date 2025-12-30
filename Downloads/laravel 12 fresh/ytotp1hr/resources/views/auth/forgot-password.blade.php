{{-- forgot-password.blade --}}

<h2>Forgot Password</h2>

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

<form action="{{ route('forgot-password.submit') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="Enter your email" required>
    <br><br>
    <button type="submit">Send OTP</button>
</form>
