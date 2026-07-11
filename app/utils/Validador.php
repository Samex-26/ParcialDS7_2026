<?php
namespace App\Utils;

class Validador {
    public static function validarRequerido($value) {
        return !empty(trim($value));
    }
    
    public static function validarIdentidad($value) {
        return preg_match('/^[0-9]{2}-[0-9]{4}-[0-9]{4}$/', $value);
    }
    
    public static function validarCorreo($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validarCelular($value) {
        return preg_match('/^[0-9]{8}$/', $value);
    }
    
    public static function validarEdad($value) {
        $edad = (int)$value;
        return $edad >= 18 && $edad <= 120;
    }
    
    public static function validarSexo($value) {
        return in_array($value, ['Masculino', 'Femenino', 'Otro']);
    }
    
    public static function validarNacion($value) {
        return is_numeric($value) && $value > 0;
    }

    public static function validarIdSeleccion($value) {
        return is_numeric($value) && $value > 0;
    }

    public static function validarSalario($value) {
        return is_numeric($value) && $value >= 0;
    }

    public static function validarFecha($value) {
        $date = \DateTime::createFromFormat('Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value;
    }
}
?>
