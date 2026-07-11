<?php
// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) require $file;
    }
});

// Enrutamiento
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^/ParcialDSF7#', '', $path);
$path = preg_replace('#^/public#', '', $path);
$path = preg_replace('#^/index\.php#', '', $path);
$path = $path ?: '/';

$controller = new App\Controllers\ControladorColaborador();

//switch
switch ($path) {
    case '/':
    case '/formulario':
        $controller->mostrarFormulario();
        break;
    case '/guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->registrar();
        } else {
            echo " Método no permitido";
        }
        break;
    case '/reporte':
        $controller->mostrarReporte();
        break;
    case '/exportar-excel':
        $controller->descargarExcel();
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada: " . $path;
}
?>
