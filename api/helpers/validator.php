<?php
/*
 *	Clase para validar todos los datos de entrada del lado del servidor.
 */
class Validator
{
    // Propiedades para manejar algunas validaciones.
    private static $passwordError = null;
    private static $fileError = null;
    private static $sessionError = null;
    private static $fileName = null;

    /*
     *   Método para obtener el error al validar una contraseña.
     */
    public static function getPasswordError()
    {
        return self::$passwordError;
    }

    /*
     *   Método para obtener el nombre del archivo validado previamente.
     */
    public static function getFileName()
    {
        return self::$fileName;
    }

    /*
     *   Método para obtener el error al validar un archivo.
     */
    public static function getFileError()
    {
        return self::$fileError;
    }

    /*
     *   Método para obtener el error al validar un archivo.
     */
    public static function getSessionError()
    {
        return self::$sessionError;
    }

    /*
     *   Método para sanear todos los campos de un formulario (quitar los espacios en blanco al principio y al final).
     *   Parámetros: $fields (arreglo con los campos del formulario).
     *   Retorno: arreglo con los campos saneados del formulario.
     */
    public static function validateForm($fields)
    {
        foreach ($fields as $index => $value) {
            $value = trim($value);
            $fields[$index] = $value;
        }
        return $fields;
    }

    /*
     *   Método para validar un número natural como por ejemplo llave primaria, llave foránea, entre otros.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateNaturalNumber($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a uno.
        if (filter_var($value, FILTER_VALIDATE_INT, array('min_range' => 1))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un número del año.
     */
    public static function validateAnio($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a uno.
        if (filter_var($value, FILTER_VALIDATE_INT, array('min_range' => 2010))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo de imagen.
     *   Parámetros: $file (archivo de un formulario), $maxWidth (ancho máximo para la imagen) y $maxHeigth (alto máximo para la imagen).
     *   Retorno: booleano (true si el archivo es correcto o false en caso contrario).
     */
    public static function validateImageFile($file, $maxWidth, $maxHeigth)
    {
        // Se obtienen las dimensiones y el tipo de la imagen.
        list($width, $height, $type) = getimagesize($file['tmp_name']);
        // Se comprueba si el archivo tiene un tamaño mayor a 2MB.
        if ($file['size'] > 2097152) {
            self::$fileError = 'El tamaño de la imagen debe ser menor a 2MB';
            return false;
        } elseif ($width > $maxWidth || $height > $maxHeigth) {
            self::$fileError = 'La dimensión de la imagen es incorrecta';
            return false;
        } elseif ($type == 2 || $type == 3) {
            // Se obtiene la extensión del archivo y se convierte a minúsculas.
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            // Se establece un nombre único para el archivo.
            self::$fileName = uniqid() . '.' . $extension;
            return true;
        } else {
            self::$fileError = 'El tipo de imagen debe ser jpg o png';
            return false;
        }
    }

    /*
     *   Método para validar un correo electrónico.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato booleano.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateBoolean($value)
    {
        if ($value == 1 || $value == 0 || $value == true || $value == false) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una cadena de texto (letras, digitos, espacios en blanco y signos de puntuación).
     *   Parámetros: $value (dato a validar), $minimum (longitud mínima) y $maximum (longitud máxima).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateString($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\,\;\.\/]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }


    //validar un string solo con que tenga el tamaño adecuado

    public static function validateNormal($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato alfabético (letras y espacios en blanco).
     *   Parámetros: $value (dato a validar), $minimum (longitud mínima) y $maximum (longitud máxima).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateAlphabetic($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato alfanumérico (letras, dígitos y espacios en blanco).
     *   Parámetros: $value (dato a validar), $minimum (longitud mínima) y $maximum (longitud máxima).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateAlphanumeric($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato monetario.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateMoney($value)
    {
        // Se verifica que el número tenga una parte entera y como máximo dos cifras decimales.
        if (preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una contraseña.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePassword($value)
    {
        // Se verifica la longitud mínima.
        if (strlen($value) < 8) {
            self::$passwordError = 'Clave menor a 8 caracteres';
            return false;
        } elseif (strlen($value) > 72) {
            self::$passwordError = 'Clave mayor a 72 caracteres';
            return false;
        } elseif (!preg_match('`[a-z]`', $value)) {
            self::$passwordError = "La clave debe tener al menos una letra minúscula";
            return false;
        } elseif (!preg_match('`[A-Z]`', $value)) {
            self::$passwordError = "La clave debe tener al menos una letra mayúscula";
            return false;
        } elseif (!preg_match('`[0-9]`', $value)) {
            self::$passwordError = "La clave debe tener al menos un caracter numérico";
            return false;
        } elseif (preg_match('`[\W]`', $value)) {
            return true;
        } else {
            self::$passwordError = "La clave debe tener al menos un caracter especial";
            return false;
        }
    }

    /*
     *   Método para validar el formato del DUI (Documento Único de Identidad).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDUI($value)
    {
        // Se verifica que el número tenga el formato 00000000-0.
        if (preg_match('/^[0-9]{8}[-][0-9]{1}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un número telefónico.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePhone($value)
    {
        // Se verifica que el número tenga el formato 0000-0000 y que inicie con 2, 6 o 7.
        if (preg_match('/^[2,6,7]{1}[0-9]{3}[-][0-9]{4}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una fecha.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDate($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo en el siguiene orden: año, mes y día.
        $date = explode('-', $value);
        if (checkdate($date[1], $date[2], $date[0])) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de subirlo al servidor.
     *   Parámetros: $file (archivo), $path (ruta del archivo) y $name (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
     */
    public static function saveFile($file, $path, $name)
    {
        // Se verifica que el archivo sea movido al servidor.
        if (move_uploaded_file($file['tmp_name'], $path . $name)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de subirlo al servidor.
     *   Parámetros: $file (archivo), $path (ruta del archivo) y $name (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
     */
    public static function saveFilePreview($file, $path, $name)
    {
        // Se verifica que el archivo sea movido al servidor.
        if (move_uploaded_file($file['tmp_name'], $path . $name)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de borrarlo del servidor.
     *   Parámetros: $path (ruta del archivo) y $name (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue borrado del servidor o false en caso contrario).
     */
    public static function deleteFile($path, $name)
    {
        // Se comprueba que el archivo sea borrado del servidor.
        if (@unlink($path . $name)) {
            return true;
        } else {
            return false;
        }
    }


    public static function deleteFilePreview($path)
    {
        // Se comprueba que el archivo sea borrado del servidor.
        if (@unlink($path)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo PDF.
     *   Parámetros: $file (archivo de un formulario).
     *   Retorno: booleano (true si el archivo es correcto o false en caso contrario).
     */
    public static function validatePDFFile($file)
    {
        // Se comprueba si el archivo tiene un tamaño mayor a 2MB.
        if ($file['size'] > 2097152) {
            self::$fileError = 'El tamaño del archivo debe ser menor a 2MB';
            return false;
        } elseif (mime_content_type($file['tmp_name']) == 'application/pdf') {
            // Se obtiene la extensión del archivo y se convierte a minúsculas.
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            // Se establece un nombre único para el archivo.
            self::$fileName = uniqid() . '.' . $extension;
            return true;
        } else {
            self::$fileError = 'El tipo de archivo debe ser PDF';
            return false;
        }
    }

    public static function validateSessionTime()
    {
        //Tiempo en segundos para dar vida a la sesión.
        $inactivo = 300; //Tiempo en segundos.

        //Calculamos tiempo de vida inactivo.
        $vida_session = time() - $_SESSION['tiempo'];

        //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
        if ($vida_session > $inactivo) {
            //Destruimos sesión.
            session_destroy();
            //Redirigimos pagina.
            //self::$sessionError = 'Su sesión ha caducado';
            return false;
        } else { // si no ha caducado la sesion, actualizamos
            $_SESSION['tiempo'] = time();
            return true;
        }
    }

    public static function validateAttemptsCd($tiempo)
    {
        if ($tiempo != null) {
            //Tiempo en segundos para dar vida a la sesión.
            $temporizador = 30; //Tiempo en segundos.

            //Calculamos tiempo de vida inactivo.
            $cuenta = time() - $tiempo;

            //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
            if ($cuenta > $temporizador) {
                //Destruimos sesión.
                return true;
                //Redirigimos pagina.
                //self::$sessionError = 'Su sesión ha caducado';
            } else { // si no ha caducado la sesion, actualizamos
                return false;
            }
        } else {
            return true;
        }
    }
}
