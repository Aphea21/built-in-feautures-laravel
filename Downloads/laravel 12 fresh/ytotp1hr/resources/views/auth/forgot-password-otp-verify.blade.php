<h2>Forgot Password - Verify OTP</h2>

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

<form action="{{ route('verify-forgot-password-otp') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">

    <label for="otp">Enter OTP:</label>
    <input type="text" name="otp" id="otp" placeholder="Enter OTP" maxlength="6" required>
    <br><br>

    <button type="submit">Submit</button>
</form>


<br>

<form action="{{ route('resend-otp') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">
    <button type="submit">Resend OTP</button>
</form>
