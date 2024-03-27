<?php

class CommentController
{
    private static $tableComments = 'user_comment';
    private static $tableusers = 'user';

    public static function createComment($user, $coment_text, $likes)
    {
        $date = date('Y-m-d H:i:s');
        try {
            $checkuser = self::userExist($user);
            if ($checkuser == 0) {
                throw new Exception("No existe el usuario");
            }
            $query = "INSERT INTO " . self::$tableComments . " (user , coment_text, likes, creation_date, update_date) VALUES (?, ?, ?, ?, ?)";
            $params = ["isiss", $user, $coment_text, $likes, $date, $date];
            Db::executeQuery($query, $params);
            $insertedId = Db::connect()->insert_id;
            if ($insertedId > 0) {
                return array("error" => 0, "mensaje" => "Comentario creado correctamente", "id" => $insertedId);
            } else {
                return array("error" => 1, "mensaje" => "Error al crear el comentario");
            }
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }
    public static function deleteComment($id)
    {
        try {
            $query = "DELETE FROM " . self::$tableComments . " WHERE id = ?";
            $params = ["i", $id];
            $result = Db::executeQuery($query, $params, true);
            if ($result > 0) {
                return array("error" => 0, "mensaje" => "Comentario borrado correctamente");
            } else {
                return array("error" => 1, "mensaje" => "Comentario no encontrado");
            }
            return array("error" => 0, "mensaje" => "Comentario borrado correctamente");
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }
    private static function userExist($id)
    {
        $query = "SELECT COUNT(id) as count FROM " . self::$tableusers . " WHERE id = ?";
        $params = ["i", $id];
        $result = Db::executeQuery($query, $params);
        $row = $result->fetch_assoc();
        return intval($row['count']);
    }
    public static function updateComment($id, $user = 0, $coment_text = null, $likes = null)
    {
        try {
            $update_date = date('Y-m-d H:i:s');
            $query = "UPDATE " . self::$tableComments . " SET update_date=?";
            
            $params = ["s", $update_date];
            
            if ($user !== 0) {
                $query .= ", user = ?";
                $params[0] .= "i";
                $params[] = $user;
                $checkuser = self::userExist($user);
                if ($checkuser == 0) {
                    throw new Exception("No existe el usuario");
                }
            }
            if ($coment_text !== null) {
                $query .= ", coment_text = ?";
                $params[0] .= "s";
                $params[] = $coment_text;
            }
            if ($likes !== null) {
                $query .= ", likes = ?";
                $params[0] .= "i";
                $params[] = $likes;
            }

            $query .= " WHERE id = ?";
            $params[0] .= "i";
            $params[] = $id;

            $result = Db::executeQuery($query, $params, true);
            if ($result <= 0) {
                throw new Exception("Comentario no encontrado");
            }
            return array("error" => 0, "mensaje" => "Comentario actualizado correctamente");
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }
    public static function getComment($id)
    {
        try {
            $query = "SELECT a.id, a.user, a.coment_text, a.likes, a.creation_date, a.update_date, b.fullname FROM " . self::$tableComments . " as a inner join " . self::$tableusers . " as b on a.user =b.id WHERE a.id = ?";
            $params = ["i", $id];
            $result = Db::executeQuery($query, $params);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }
    public static function getallComments()
    {
        try {
            $query = "SELECT a.id, a.user, a.coment_text, a.likes, a.creation_date, a.update_date, b.fullname, b.email FROM " . self::$tableComments . " as a inner join " . self::$tableusers . " as b on a.user =b.id order by a.id desc";
            $result = Db::executeQuery($query, null);
            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
            return $comments;
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }
    public static function incrementLike($id)
    {
        try {
            $query = "UPDATE " . self::$tableComments . " SET likes = likes + 1 WHERE id = ?";
            $params = ["i", $id];
            $result = Db::executeQuery($query, $params, true);

            if ($result <= 0) {
                throw new Exception("Comentario no encontrado");
            }

            return array("error" => 0, "mensaje" => "Like incrementado correctamente");
        } catch (Exception $e) {
            return array("error" => 1, "mensaje" => "Error: " . $e->getMessage());
        }
    }
}
