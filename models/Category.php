<?php
class Categories
{
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;
    public $update_category;
    public $update_id;

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

    // Read all categories
    public function read_categories()
    {
        $query = 'SELECT
				id,
				category
			FROM
				' . $this->table . '
			ORDER BY
				id ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single category

    public function read_single()
    {
        $query = 'SELECT
				id,
				category
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
            $this->category = $row['category'];

            return true;
        }
        return false;
    }

    // Create category

    public function create()
    {
        $query = 'INSERT INTO '
            . $this->table .
            '(category)
			VALUES(:category)
            RETURNING id';

        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update category

    public function update()
    {
        $query = 'UPDATE '
            . $this->table .
            ' SET
				category = :category
			WHERE
				id = :id';

        $stmt = $this->conn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        $new_category = array('category' => $this->update_category, 'id' => $this->update_id);


        if ($stmt->execute()) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }
            $this->update_category = $this->category;
            $this->update_id = $this->id;

            return json_encode($new_category);
        }

        printf("Error: %s.\n", $stmt->error);
        return false;

    }

    // Delete category

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