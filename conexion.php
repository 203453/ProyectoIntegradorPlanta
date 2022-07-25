<?php
    class conexion {
        const user = 'admin';
        const pass = 'emi203453';
        const dbName = 'integrador';
        const server = 'localhost';

        public function conectarDb() {
            $conectar = new mysqli(self::server, self::user, self::pass, self::dbName);
            if($conectar->connect_errno) {
                die("Error en la conexion, ".$conectar->connect_error);
            }
            return $conectar;
        }
    }
?>