<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FraudShield</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-2xl shadow-2xl p-8 hover:border-purple-500/50 transition-all duration-300">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/25">
                <span class="text-3xl">🛡️</span>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                FraudShield
            </h1>
            <p class="text-slate-400 mt-2">Welcome back</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6">
                {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400 transition-all duration-200"
                    placeholder="Enter your email"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400 transition-all duration-200"
                    placeholder="Enter your password"
                >
            </div>
            
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all duration-200 shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40"
            >
                Sign In
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-slate-400 text-sm">
                Use: admin@frauddetection.com / admin123
            </p>
        </div>
    </div>
</body>
</html>
