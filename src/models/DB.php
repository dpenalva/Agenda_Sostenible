<?php
namespace Models;

class DB {
    protected $sql;
    
    public function __construct($config) {
        try {
            // Verificar que todas las claves necesarias estén presentes
            $required = ['host', 'name', 'user', 'pass'];
            foreach ($required as $key) {
                if (!isset($config[$key])) {
                    throw new \Exception("Falta la configuración de base de datos: $key");
                }
            }

            $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";
            
            if (isset($config['port'])) {
                $dsn .= ";port={$config['port']}";
            }

            $this->sql = new \PDO(
                $dsn,
                $config['user'],
                $config['pass'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\PDOException $e) {
            error_log("Error de conexión PDO: " . $e->getMessage());
            throw new \Exception("Error de conexión a la base de datos");
        } catch (\Exception $e) {
            error_log("Error de configuración: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    protected function handleError($stmt, $query) {
        if (!$stmt) {
            $error = $this->sql->errorInfo();
            error_log("Error en la consulta: " . $query . " - " . implode(", ", $error));
            throw new \Exception("Error en la base de datos");
        }
    }
} 