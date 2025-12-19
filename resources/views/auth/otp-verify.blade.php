<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please enter the OTP sent to your email address for verification.') }}
    </div>

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div class="mb-4">
            <label for="otp" class="block font-medium text-sm text-gray-700">{{ __('Enter OTP') }}</label>
            <input type="text" name="otp" id="otp" class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:ring focus:ring-indigo-200" required autofocus>
             
            @error('otp')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <x-primary-button>
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
