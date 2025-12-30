
<form action="{{ route('login') }}" method="POST">
    @csrf

    <input type="email" name="email" placeholder="Email" required>
    <br><br>

    <input type="password" name="password" placeholder="Password" required>
    <br><br>


    <button type="submit">Register</button>
    <a href="{{ route('forgot-password') }}">Forgot Password?</a>

</form>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color: red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif