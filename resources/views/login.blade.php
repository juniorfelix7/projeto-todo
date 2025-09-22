<x-layout page="Login">
    <x-slot:btn>
        <a href="{{route('register')}}" class="btn btn-primary">
            Cadastrar
        </a>
    </x-slot:btn>    
    
    <section id="login_section">
        <h1>Fazer Login</h1>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="inputArea">
                <label for="email">
                    E-mail
                </label>
                <input type="email" name="email" placeholder="Digite seu e-mail" value="{{ old('email') }}" required />
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="password">
                    Senha
                </label>
                <input type="password" name="password" placeholder="Digite sua senha" required />
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <button type="submit" class="btn btn-primary">
                    Entrar
                </button>
            </div>
            
            <div class="auth-links">
                <p>Não tem uma conta? <a href="{{ route('register') }}">Cadastre-se aqui</a></p>
            </div>
        </form>
    </section>
</x-layout>