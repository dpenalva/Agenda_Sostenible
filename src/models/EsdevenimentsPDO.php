<?php
namespace Models;

class EsdevenimentsPDO {
    private $sql;

    public function __construct($config) {
        $this->sql = new \PDO(
            "mysql:host=" . $config['host'] . ";dbname=" . $config['name'],
            $config['user'],
            $config['pass'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
    }

    /**
     * Obtiene todos los eventos ordenados por fecha
     */
    public function getAll() {
        try {
            $stmt = $this->sql->prepare("
                SELECT 
                    id,
                    titol,
                    descripcio,
                    longitud,
                    latitud,
                    data_esdeveniment,
                    hora_esdeveniment,
                    visibilitat_esdeveniment,
                    tipus_esdeveniment_id,
                    id_usuari
                FROM esdeveniments
                ORDER BY data_esdeveniment DESC, hora_esdeveniment DESC
            ");
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error al obtener eventos: " . $e->getMessage());
            throw new \Exception("Error al obtener los eventos");
        }
    }

    /**
     * Obtiene todos los eventos de un usuario específico
     */
    public function getByUser($userId) {
        try {
            $query = "SELECT 
                        e.id,
                        e.titol,
                        e.descripcio,
                        e.data_inici,
                        e.hora_inici,
                        e.ubicacio,
                        e.aforament,
                        e.tipus_esdeveniment_id,
                        e.imatge_url,
                        e.created_at,
                        e.updated_at,
                        u.nom_usuari as creador_nom,
                        u.imatge_perfil as creador_avatar
                    FROM esdeveniments e
                    INNER JOIN usuaris u ON e.usuari_id = u.id
                    WHERE e.usuari_id = :userId
                    ORDER BY e.data_inici DESC, e.hora_inici DESC";

            $stmt = $this->sql->prepare($query);
            $stmt->execute([':userId' => $userId]);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getByUser: " . $e->getMessage());
            throw new \Exception("Error al obtener los eventos del usuario");
        }
    }

    /**
     * Obtiene un evento específico
     */
    public function get($id) {
        try {
            $query = "SELECT 
                        e.*,
                        u.nom_usuari as creador_nom,
                        u.imatge_perfil as creador_avatar
                    FROM esdeveniments e
                    INNER JOIN usuaris u ON e.usuari_id = u.id
                    WHERE e.id = :id";

            $stmt = $this->sql->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $event = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$event) {
                throw new \Exception("Evento no encontrado");
            }
            
            return $event;
        } catch (\PDOException $e) {
            error_log("Error en get: " . $e->getMessage());
            throw new \Exception("Error al obtener el evento");
        }
    }

    /**
     * Crea un nuevo evento
     */
    public function create($eventData) {
        try {
            $stmt = $this->sql->prepare("
                INSERT INTO esdeveniments (
                    titol, 
                    descripcio, 
                    longitud, 
                    latitud, 
                    data_esdeveniment, 
                    hora_esdeveniment, 
                    visibilitat_esdeveniment,
                    id_usuari
                ) VALUES (
                    :titol, 
                    :descripcio, 
                    :longitud, 
                    :latitud, 
                    :data_esdeveniment, 
                    :hora_esdeveniment, 
                    :visibilitat_esdeveniment,
                    :id_usuari
                )
            ");

            $stmt->execute([
                ':titol' => $eventData['titol'],
                ':descripcio' => $eventData['descripcio'],
                ':longitud' => $eventData['longitud'],
                ':latitud' => $eventData['latitud'],
                ':data_esdeveniment' => $eventData['data_esdeveniment'],
                ':hora_esdeveniment' => $eventData['hora_esdeveniment'],
                ':visibilitat_esdeveniment' => $eventData['visibilitat_esdeveniment'] ? 1 : 0,
                ':id_usuari' => $_SESSION['user_id']
            ]);

            return $this->sql->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Error al crear evento: " . $e->getMessage());
            throw new \Exception("Error al crear el evento");
        }
    }

    public function getTotalEvents() {
        try {
            $query = "SELECT COUNT(*) as total FROM esdeveniments";
            $stmt = $this->sql->query($query);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (\PDOException $e) {
            error_log("Error en getTotalEvents: " . $e->getMessage());
            return 0;
        }
    }

    public function getRecentEvents($limit = 5) {
        try {
            $query = "SELECT id, titol, descripcio, data_esdeveniment, hora_esdeveniment 
                      FROM esdeveniments 
                      ORDER BY data_esdeveniment DESC, hora_esdeveniment DESC 
                      LIMIT :limit";
            
            $stmt = $this->sql->prepare($query);
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error en getRecentEvents: " . $e->getMessage());
            return [];
        }
    }
} 