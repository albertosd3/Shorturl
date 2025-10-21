@extends('layouts.app')

@section('title', 'Login - Short URL Manager')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="text-4xl font-bold text-blue-400 mb-2">Short URL Manager</h2>
            <p class="text-gray-400">Enter your password to access the dashboard</p>
        </div>
        
        <div class="glass-effect rounded-2xl p-8">
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your password"
                        autofocus
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    Access Dashboard
                </button>
            </form>
        </div>
        
        <div class="text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} Short URL Manager. Simple. Secure. Elegant.
            </p>
        </div>
    </div>
</div>

<script>
    // Add some subtle animations
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const container = document.querySelector('.glass-effect');
        
        form.addEventListener('submit', function() {
            container.style.opacity = '0.7';
            container.style.transform = 'scale(0.98)';
        });
    });
</script>
@endsection