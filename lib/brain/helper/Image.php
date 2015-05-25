<?php
namespace Brain\Helper;


class Image {

    public static function redimensionaImagem($imagemOriginal, $imagemNova, $larguraMaxima, $alturaMaxima, $opcao, $qualidade)
    {
        // 1. Variáveis
        list($larguraOriginal, $alturaOriginal) = getimagesize($imagemOriginal);
        $larguraNova = $larguraOriginal;
        $alturaNova  = $alturaOriginal;

        // 2. Verifica se é necessário redimensionar a imagem
        if ($larguraOriginal > $larguraMaxima || $alturaOriginal > $alturaMaxima)
        {

            // 2.1. Descobre a porcentagem da dimensão máxima em relação a dimensão original
            $difPctLar = ($larguraMaxima / $larguraOriginal) * 100;
            $difPctAlt = ($alturaMaxima  / $alturaOriginal)  * 100;

            // 2.2. Verifica em qual dimensão o cálculo deve se basear para obter a porcentagem para redimensionar a imagem
            if ($opcao == 2)
                $pct = ($difPctLar < $difPctAlt) ? $difPctLar : $difPctAlt;
            else
                $pct = ($difPctLar > $difPctAlt) ? $difPctLar : $difPctAlt;

            // 2.3. Calcula as novas dimensões da imagem
            $larguraNova = round(($larguraOriginal / 100) * $pct);
            $alturaNova  = round(($alturaOriginal  / 100) * $pct);
        }

        // 3. Cria a imagem redimensionada
        $novaImagem  = imagecreatefromjpeg($imagemOriginal);

        if ($opcao == 1)
            $imagemFinal = imagecreatetruecolor($larguraMaxima, $alturaMaxima);
        else
            $imagemFinal = imagecreatetruecolor($larguraNova, $alturaNova);

        imagecopyresampled($imagemFinal, $novaImagem, 0, 0, 0, 0, $larguraNova, $alturaNova, $larguraOriginal, $alturaOriginal);
        imagejpeg($imagemFinal, $imagemNova, $qualidade);

        // 4. Libera a memória usada
        imagedestroy($novaImagem);
        imagedestroy($imagemFinal);
    }
} 