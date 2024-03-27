<?php


class UserController
{
    private static $table = 'user';


    private static function recordExist($column, $value)
    {
        $query = "SELECT COUNT(" . $column . ") as count FROM " . self::$table . " WHERE " . $column . " = ?";
        $params =        ["s", $value];
        $result = Db::executeQuery($query, $params);
        $row = $result->fetch_assoc();
        return intval($row['count']);
    }
    public static function createUser($fullname, $email, $pass, $openid)
    {
        try {
            $date = date('Y-m-d H:i:s');
            $count_email = self::recordExist('email', $email);
            $count_openid        = self::recordExist('openid', $openid);
            if ($count_email > 0) {
                return array("error" => 1, "mensaje" => "Ya existe el corre, selecciona otro");
            } elseif ($count_openid > 0) {
                return array("error" => 1, "mensaje" => "Ya existe el OpenId, selecciona otro");
            }
            $query = "INSERT INTO " . self::$table . " (fullname, email, pass, openid, creation_date, update_date) VALUES (?, ?, ?, ?, ?, ?)";
            $params = ["ssssss", $fullname, $email, $pass, $openid, $date, $date];
            Db::executeQuery($query, $params);
            $insertedId = Db::connect()->insert_id;
            if ($insertedId > 0) {
                return array("error" => 0, "mensaje" => "Usuario Creado correctamente", "id" => $insertedId);
            } else {
                return array("error" => 1, "mensaje" => "Error al crear el registro");
            }
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }

    public static function deleteUser($id)
    {
        try {
            $query = "DELETE FROM " . self::$table . " WHERE id = ?";
            $params = ["i", $id];
            $result = Db::executeQuery($query, $params, true);

            if ($result > 0) {
                return array("error" => 0, "mensaje" => "Usuario borrado correctamente");
            } else {
                return array("error" => 1, "mensaje" => "Usuario no encontrado");
            }
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }

    private static function countRowsWithEmailAndId($email, $id)
    {
        $query = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE email = ? and id != ?";
        $params = ["si", $email, $id];
        $result = Db::executeQuery($query, $params);
        $row = $result->fetch_assoc();
        return intval($row['count']);
    }
    private static function countRowsOpenId($openid, $id)
    {
        $query = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE openid = ?  and id != ?";
        $params = ["si", $openid, $id];
        $result = Db::executeQuery($query, $params);
        $row = $result->fetch_assoc();
        return intval($row['count']);
    }

    public static function updateUser($id, $fullname = null, $email = null, $pass = null, $openid = null)
    {
        try {
            if ($id === null) {
                return array("error" => 1, "mensaje" => "Se requiere el ID para actualizar el usuario");
            }
            $update_date = date('Y-m-d H:i:s');
            if ($email !== null) {
                $count_email = self::countRowsWithEmailAndId($email, $id);
                if ($count_email > 0) {
                    return array("error" => 1, "mensaje" => "Ya existe el correo, selecciona otro");
                }
            }
            
            if ($openid !== null) {
                $count_openid = self::countRowsOpenId($openid, $id);
                if ($count_openid > 0) {
                    return array("error" => 1, "mensaje" => "Ya existe el OpenID, selecciona otro");
                }
            }
            $query = "UPDATE " . self::$table . " SET update_date=?";
            $params = ["s", $update_date];

            if ($fullname !== null) {
                $query .= ", fullname = ?";
                $params[0] .= "s";
                $params[] = $fullname;
            }
            if ($email !== null) {
                $query .= ", email= ?";
                $params[0] .= "s";
                $params[] = $email;
            }
            if ($pass !== null) {
                $query .= ", pass= ?";
                $params[0] .= "s";
                $params[] = $pass;
            }
            if ($openid !== null) {
                $query .= ", openid= ?";
                $params[0] .= "s";
                $params[] = $openid;
            }


            $query .= " WHERE id = ?";
            $params[0] .= "i";
            $params[] = $id;

            $result = Db::executeQuery($query, $params, true);

            if ($result > 0) {
                return array("error" => 0, "mensaje" => "Usuario actualizado correctamente");
            } else {
                return array("error" => 1, "mensaje" => "Usuario no encontrado");
            }
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }

    public static function getUser($id)
    {
        $query = "SELECT * FROM " . self::$table . " WHERE id = ?";
        $params = ["i", $id];
        $result = Db::executeQuery($query, $params);
        return $result->fetch_assoc();
    }
    public static function getUserbyEmail($email)
    {
        $query = "SELECT * FROM " . self::$table . " WHERE email = ?";
        $params = ["s", $email];
        $result = Db::executeQuery($query, $params);
        return $result->fetch_assoc();
    }
    public static function RegisterOpenId($openid, $email, $fullname, $pass)
    {
        try {
            $date =  date('Y-m-d H:i:s');
            $count_email = self::recordExist('email', $email);
            if ($count_email > 0) {
                $query_update_openid = "UPDATE " . self::$table . " SET openid = ?, fullname=? WHERE email = ?";
                Db::executeQuery($query_update_openid, ["sss", $openid, $fullname, $email]);
                return array("error" => 0, "mensaje" =>"OpenID asociado al correo existente correctamente", "id" => $openid);
            }
            $count_openid = self::recordExist('openid', $openid);
            if ($count_openid > 0) {
                $query_update_email = "UPDATE " . self::$table . " SET email = ?, fullname=? WHERE openid = ?";
                Db::executeQuery($query_update_email, ["sss", $email, $fullname, $openid]);
                return array("error" => 0, "mensaje" => "Correo asociado al openid existente correctamente");
            }

            $query_insert_user = "INSERT INTO " . self::$table . " (fullname, email, pass, openid, creation_date, update_date) VALUES (?, ?, ?, ?, ?, ?)";
            $params_insert_user = ["ssssss", $fullname, $email, $pass, $openid, $date, $date];
            Db::executeQuery($query_insert_user, $params_insert_user);
            $insertedId = Db::connect()->insert_id;

            return ($insertedId > 0) ? array("error" => 0, "mensaje" => "Usuario creado correctamente", "id" => $insertedId) : array("error" => 1, "mensaje" => "Error al crear el usuario");
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }

    public static function getAllUsers()
    {
        $query = "SELECT * FROM " . self::$table;
        $result = Db::executeQuery($query, null);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
}
