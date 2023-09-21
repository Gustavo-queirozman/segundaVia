<?php
If(Auth::user()->is_admin == '1') {?>
    <input type="text" placeholder="Digite cnp...">
    <input type="submit" value="enviar">
<?php
}
dd($boletos);
?>


