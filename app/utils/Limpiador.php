<?php
namespace App\Utils;

class Limpiador {
    public static function limpiarCadena($value) {
        return trim(htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8'));
    }
    
    public static function limpiarIdentidad($value) {
        return preg_replace('/[^0-9-]/', '', trim($value));
    }
    
    public static function limpiarCorreo($value) {
        return filter_var(trim($value), FILTER_SANITIZE_EMAIL);
    }
    
    public static function limpiarCelular($value) {
        return preg_replace('/[^0-9]/', '', trim($value));
    }
    
    public static function limpiarEdad($value) {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
    
    public static function limpiarNombre($value) {
        $value = self::limpiarCadena($value);
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }
    
    public static function limpiarObservaciones($value) {
        return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }

    public static function limpiarSalario($value) {
        $value = str_replace([',', ' '], ['.', ''], trim($value));
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function limpiarFecha($value) {
        return trim($value);
    }

    public static function limpiarIdSeleccion($value) {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
}
?>