<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Please enter the OTP sent to your email.
    </div>

    <form method="POST" action="{{ route('otp.verify.submit') }}">
        @csrf

        <input
            type="text"
            name="otp"
            class="block w-full mt-1"
            required
            autofocus
            placeholder="6-digit OTP"
        >

        @error('otp')
            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
        @enderror

        <x-primary-button class="mt-4">
            Verify OTP
        </x-primary-button>
    </form>

<form method="POST" action="{{ route('otp.resend') }}">
    @csrf
    <button type="submit" class="text-sm text-blue-600 hover:underline mt-2">
        Resend OTP
    </button>
</form>

</x-guest-layout>
