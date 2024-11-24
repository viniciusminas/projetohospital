<?php
function sanitizarEntrada($entrada) {
    return htmlspecialchars(trim($entrada), ENT_QUOTES, 'UTF-8');
}

function validarResposta($resposta) {
    return is_numeric($resposta) && $resposta >= 0 && $resposta <= 10;
}
?>
