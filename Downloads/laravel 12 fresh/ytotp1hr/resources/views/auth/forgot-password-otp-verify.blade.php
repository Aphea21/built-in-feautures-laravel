<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#F8FAFC]">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8 border border-[#E2E8F0]">
            <h2 class="text-2xl font-bold text-[#0F172A] mb-6 text-center">Verify OTP</h2>
            <p class="text-[#475569] text-sm mb-4 text-center">
                Enter the OTP sent to your email address.
            </p>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password-otp.verify.submit') }}">
                @csrf
                <div class="mb-4">
                    <label for="otp" class="block text-[#475569] font-medium mb-1">OTP Code</label>
                    <input type="text" name="otp" id="otp" required autofocus
                        class="w-full border border-[#E2E8F0] rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('otp')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                    Verify OTP
                </button>
            </form>

            <form method="POST" action="{{ route('otp.resend') }}" class="mt-4 text-center">
                @csrf
                <button type="submit" class="text-indigo-600 text-sm hover:underline">
                    Resend OTP
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
