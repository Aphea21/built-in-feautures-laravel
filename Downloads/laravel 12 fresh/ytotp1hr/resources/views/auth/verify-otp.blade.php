@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color: red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('verify-otp') }}" method="POST">
    @csrf

    <input type="hidden" name="email;" value="{{$email}}">
    <br><br>

   <label for="otp">Enter OTP:</label>

    <input type="password" 
    id="otp"
    name="otp" placeholder="Enter OTP" required>
    <br><br>
<fqqorm action="{{ route('resend-otp') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{$email}}">
    <button type="submit">Resend OTP</button>
</form>
