<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Toko Obat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-50 min-h-screen">
  <div class="min-h-screen flex items-center justify-center p-5">
    <form class="bg-white border border-zinc-200 rounded-xl p-10 max-w-md w-full shadow-sm" method="POST" action="{{ route('login.store') }}">
      @csrf
      
      <h1 class="text-2xl font-extrabold mb-2">Login</h1>
      <p class="text-zinc-500 text-sm mb-6">Silakan masuk dengan akun Anda</p>
      
      @if(session('error'))
        <div class="bg-red-50 text-red-600 px-3 py-2.5 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
      @endif
        
      <label class="block text-sm font-medium mb-1.5">Username</label>
      <input type="text" name="username" value="{{ old('username') }}" class="{{ $errors->has('username') ? 'border-red-500' : 'border-gray-500' }} w-full px-3.5 py-3 border border-zinc-200 rounded-lg text-sm mb-4 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/5" autofocus required />
      @error('username')
        <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}</p>
      @enderror
      
      <label class="block text-sm font-medium mb-1.5">Password</label>
      <input type="password" name="password" class="{{ $errors->has('password') ? 'border-red-500' : 'border-gray-500' }} w-full px-3.5 py-3 border border-zinc-200 rounded-lg text-sm mb-4 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/5" required />
      @error('password')
        <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}</p>
      @enderror

      <button class="w-full px-4 py-3 rounded-lg bg-black text-white font-bold text-sm hover:bg-zinc-800 transition-colors" type="submit">Masuk</button>
    </form>
  </div>
<script>
const form = document.querySelector('form');

document.querySelector('button[type=submit]').addEventListener('click', function() {
    let allInputFilled = true
    const inputs = form.querySelectorAll("input, select"); // ambil semua input
    inputs.forEach(input => {
        if (input.type !== 'hidden' && !input.value.trim()) {
            allInputFilled = false 
        }
    });
    if(allInputFilled) {
        setTimeout(() => {
            this.disabled = true;
            this.style.opacity = '0.5';
            this.style.cursor = 'not-allowed';
        }, 1)
    }
    
})
</script>
</body>
</html>


