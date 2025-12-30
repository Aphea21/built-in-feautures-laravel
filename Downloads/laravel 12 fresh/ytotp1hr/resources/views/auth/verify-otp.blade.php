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

{{-- VERIFY OTP FORM --}}
<form action="{{ route('verify-otp') }}" method="POST">
    @csrf

    <input type="hidden" name="email" value="{{ $email }}">

    <label for="otp">Enter OTP:</label>
    <input
        type="text"
        id="otp"
        name="otp"
        placeholder="Enter OTP"
        required
    >

    <br><br>

    <button type="submit">Submit</button>
</form>

<br>

{{-- RESEND OTP FORM --}}
<form action="{{ route('resend-otp') }}" method="POST">
    @csrf

    <input type="hidden" name="email" value="{{ $email }}">

    <button type="submit">Resend OTP</button>
</form>
