<?php
class Db
{

    private $link;
    private $engine;
    private $host;
    private $name;
    private $user;
    private $pass;
    private $charset;
    private $options;

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        $this->engine   = IS_LOCAL ? LDB_ENGINE : DB_ENGINE;
        $this->host     = IS_LOCAL ? LDB_ENGINE : DB_HOST;
        $this->name     = IS_LOCAL ? LDB_ENGINE : DB_NAME;
        $this->user     = IS_LOCAL ? LDB_ENGINE : DB_USER;
        $this->pass     = IS_LOCAL ? LDB_ENGINE : DB_PASS;
        $this->charset  = IS_LOCAL ? LDB_ENGINE : DB_CHARSET;
        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        return $this;
    }

    /**
     * Conectar a la base de datos
     *
     * @return mixed
     */

    private function connect()
    {
        try {
            $this->link = new PDO($this->engine . ':host=' . $this->host . ';dbname=' . $this->name . ';charset=' . $this->charset, $this->user, $this->pass, $this->options);
            return $this->link;
        } catch (PDOException $e) {
            die(sprintf('No se pudo conectar a la base de datos. Error: %s', $e->getMessage()));
        }
    }
    /**
     * Método para hacer un query a la base de datos
     * @param string $sql
     * @param array $params
     * @return void
     */

    public static function query($sql, $params = [])
    {
        $db = new self();
        $link = $db->connect();
        $link->beginTransaction();
        $query = $link->prepare($sql);

        //Manejando errores en el query o la petición
        if (!$query->execute($params)) {
            $link->rollBack();
            $error = $query->errorInfo();
            throw new Exception($error[2]);
        }

        //SELECT | INSERT | UPDATE | DELETE | ALTER TABLE
        //Manejando el tipo de query que se ejecuta
        if (strpos($sql, 'SELECT' !== false)) {
            return $query->rowCount() > 0 ? $query->fetchAll() : false;
        } elseif (strpos($sql, 'INSERT' !== false)) {
            $id = $link->lastInsertId();
            $link->commi();
            return $id;
        } elseif (strpos($sql, 'UPDATE') !== false) {

            $link->commit();
            return true;
        } elseif (strpos($sql, 'DELETE') !== false) {
            if ($query->rowCount() > 0) {
                $link->commit();
                return true;
            }

            $link->rollBack();
            return false; // Nada ha sido borrado
        } else {
            // ALTER TABLE | DROP TABLE 
            $link->commit();
            return true;
        }
    }
}
