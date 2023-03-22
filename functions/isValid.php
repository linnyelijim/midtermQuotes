<?php
function isValid($id, $model)
{
    $query = 'SELECT id FROM ' . $model->getTable() . ' WHERE id = :id';
    $stmt = $model->getConn()->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $count = $stmt->rowCount() > 0;
    return $count;
}


?>