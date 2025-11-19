<?php
/**
 * SecurityHelper - Clase para prevenir ataques de path traversal
 * y validar rutas de archivos de forma segura
 */
class SecurityHelper {
    
    /**
     * Obtiene la ruta base del proyecto
     */
    public static function getBasePath() {
        // Ruta absoluta al directorio raíz del proyecto
        return realpath(__DIR__ . '/..');
    }
    
    /**
     * Valida que una ruta esté dentro de los directorios permitidos
     * 
     * @param string $path Ruta a validar
     * @return string|false Ruta absoluta validada o false si es inválida
     */
    public static function securePath($path) {
        $basePath = self::getBasePath();
        
        // Convertir a ruta absoluta y resolver .. y .
        $realPath = realpath($path);
        
        // Si realpath devuelve false, el archivo no existe o la ruta es inválida
        if ($realPath === false) {
            // Intentar con la ruta base como prefijo
            $realPath = realpath($basePath . '/' . $path);
        }
        
        // Si aún es false, la ruta es inválida
        if ($realPath === false) {
            return false;
        }
        
        // Verificar que la ruta esté dentro del directorio base
        if (strpos($realPath, $basePath) !== 0) {
            return false;
        }
        
        return $realPath;
    }
    
    /**
     * Incluye un archivo de forma segura
     * 
     * @param string $path Ruta del archivo a incluir
     * @param bool $once Si debe usar require_once (true) o require (false)
     * @return bool True si se incluyó correctamente
     */
    public static function secureInclude($path, $once = true) {
        $basePath = self::getBasePath();
        
        // Si la ruta ya es absoluta y comienza con BASE_PATH, usarla directamente
        if (strpos($path, $basePath) === 0 && file_exists($path)) {
            if ($once) {
                require_once $path;
            } else {
                require $path;
            }
            return true;
        }
        
        // Intentar construir la ruta completa
        $fullPath = $path;
        if (!file_exists($fullPath)) {
            $fullPath = $basePath . '/' . ltrim($path, '/');
        }
        
        // Verificar que el archivo existe
        if (!file_exists($fullPath)) {
            error_log("SecurityHelper: Archivo no encontrado: " . $path);
            return false;
        }
        
        // Obtener la ruta real
        $realPath = realpath($fullPath);
        
        // Verificar que la ruta esté dentro del directorio base
        if ($realPath === false || strpos($realPath, $basePath) !== 0) {
            error_log("SecurityHelper: Intento de acceso a ruta no permitida: " . $path);
            return false;
        }
        
        if ($once) {
            require_once $realPath;
        } else {
            require $realPath;
        }
        
        return true;
    }
    
    /**
     * Sanitiza un valor de entrada
     * 
     * @param mixed $value Valor a sanitizar
     * @param string $type Tipo de sanitización (string, int, email, etc)
     * @return mixed Valor sanitizado
     */
    public static function sanitizeInput($value, $type = 'string') {
        switch ($type) {
            case 'int':
                return filter_var($value, FILTER_VALIDATE_INT);
            case 'email':
                return filter_var($value, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($value, FILTER_SANITIZE_URL);
            case 'string':
            default:
                return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
        }
    }
    
    /**
     * Valida que una acción esté en la lista de acciones permitidas
     * 
     * @param string $action Acción a validar
     * @param array $allowedActions Lista de acciones permitidas
     * @return bool True si la acción es válida
     */
    public static function validateAction($action, $allowedActions) {
        return in_array($action, $allowedActions, true);
    }
    
    /**
     * Obtiene un parámetro GET de forma segura
     * 
     * @param string $key Clave del parámetro
     * @param mixed $default Valor por defecto
     * @param string $type Tipo de sanitización
     * @return mixed Valor sanitizado
     */
    public static function getSecure($key, $default = null, $type = 'string') {
        if (!isset($_GET[$key])) {
            return $default;
        }
        
        return self::sanitizeInput($_GET[$key], $type);
    }
    
    /**
     * Obtiene un parámetro POST de forma segura
     * 
     * @param string $key Clave del parámetro
     * @param mixed $default Valor por defecto
     * @param string $type Tipo de sanitización
     * @return mixed Valor sanitizado
     */
    public static function postSecure($key, $default = null, $type = 'string') {
        if (!isset($_POST[$key])) {
            return $default;
        }
        
        return self::sanitizeInput($_POST[$key], $type);
    }
    
    /**
     * Previene path traversal en nombres de archivo
     * 
     * @param string $filename Nombre de archivo
     * @return string Nombre de archivo sanitizado
     */
    public static function sanitizeFilename($filename) {
        // Remover caracteres peligrosos
        $filename = str_replace(['..', '/', '\\', "\0"], '', $filename);
        return basename($filename);
    }
}
