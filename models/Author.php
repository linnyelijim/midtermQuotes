<?php
class Authors
{
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;
    public $update_author;
    public $update_id;

    //Create a constructor 
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getConn()
    {
        return $this->conn;
    }

    // Read all authors
    public function read_authors()
    {
        $query = 'SELECT
				id,
				author
			FROM
				' . $this->table . '
			ORDER BY
				id ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single author

    public function read_single()
    {
        $query = 'SELECT
				id,
				author
			FROM
				' . $this->table . '
			WHERE
				id = ?
			LIMIT 1 OFFSET 0';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row)) {
            $this->id = $row['id'];
            $this->author = $row['author'];

            return true;
        }
        return false;
    }

    // Create author

    public function create()
    {
        $query = 'INSERT INTO '
            . $this->table .
            '(author)
		VALUES(:author)
        RETURNING id';

        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update author

    public function update()
    {
        $query = 'UPDATE '
            . $this->table .
            ' SET
			    author = :author
		    WHERE
			    id = :id';

        $stmt = $this->conn->prepare($query);

        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        $new_author = array('author' => $this->update_author, 'id' => $this->update_id);

        if ($stmt->execute()) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }
            $this->update_author = $this->author;
            $this->update_id = $this->id;

            return json_encode($new_author);
        }

        printf("Error: %s.\n", $stmt->error);
        return false;

    }

    // Delete author

    public function delete()
    {
        $query = 'DELETE FROM '
            . $this->table .
            ' WHERE id = :id
            RETURNING id';

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['id'];
            } else {
                return false;
            }
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}
?>