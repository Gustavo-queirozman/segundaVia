<form method="POST" action="/">
    <h1>Login</h1>
    @csrf
    <strong>Insira cnp</strong><br>
    <input type="text" name="cnp">
    <input type="submit" value="Entrar">
    <br>
    <span style="color:red;">{{ session('mensagem') }}</span>
</form>
