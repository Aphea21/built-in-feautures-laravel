<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#F8FAFC]">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8 border border-[#E2E8F0]">
            <h2 class="text-2xl font-bold text-[#0F172A] mb-6 text-center">Reset Password</h2>
            <p class="text-[#475569] text-sm mb-4 text-center">
                Enter your new password below.
            </p>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password-otp.reset.submit') }}">
                @csrf
                <div class="mb-4">
                    <label for="password" class="block text-[#475569] font-medium mb-1">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full border border-[#E2E8F0] rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-[#475569] font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full border border-[#E2E8F0] rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
