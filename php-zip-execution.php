<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $archivo_zip = $_FILES["archivo_zip"]["tmp_name"];

    if (!empty($archivo_zip)) {
        $nombre_zip = $_FILES["archivo_zip"]["name"];

        // Verifica si el archivo es un archivo ZIP
        if (pathinfo($nombre_zip, PATHINFO_EXTENSION) == 'zip') {
            // Descomprime el archivo ZIP
            $ruta_descomprimida = "carpeta_descomprimida/";
            if (mkdir($ruta_descomprimida, 0777, true)) {
                $zip = new ZipArchive;
                if ($zip->open($archivo_zip) === TRUE) {
                    $zip->extractTo($ruta_descomprimida);
                    $zip->close();
                }

                // Ejecuta el archivo PHP si existe
                $archivo_php = $ruta_descomprimida . "archivo.php";
                if (file_exists($archivo_php)) {
                    // Ejecuta el archivo PHP
                    $resultado = shell_exec("php " . $archivo_php);

                    // Muestra el resultado
                    if ($resultado) {
                        echo "Resultado de la ejecución del archivo PHP: " . $resultado;
                    } else {
                        echo "No se pudo ejecutar el archivo PHP.";
                    }
                } else {
                    echo "No se encontró el archivo PHP en el ZIP.";
                }
            } else {
                echo "No se pudo crear la carpeta para descomprimir.";
            }
        } else {
            echo "El archivo no es un ZIP válido.";
        }
    } else {
        echo "No se ha seleccionado ningún archivo ZIP.";
    }
}
?>