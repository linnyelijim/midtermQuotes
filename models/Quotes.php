<?php
class Quotes
{
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author;
    public $category;
    public $author_id;
    public $category_id;

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

    // Read quotes
    public function read_quotes()
    {
        $query = 'SELECT 
                quotes.id, 
                quotes.quote, 
                authors.author, 
                categories.category 
			FROM 
                ' . $this->table . '
			INNER JOIN 
                authors 
            ON 
                quotes.author_id = authors.id
			INNER JOIN 
                categories 
            ON 
                quotes.category_id = categories.id
			ORDER BY id';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single quote

    public function read_single()
    {
        if (isset($_GET['id'])) {
            $query = 'SELECT
					quotes.id,
					quotes.quote,
					authors.author,
					categories.category
				FROM
					' . $this->table . '
				INNER JOIN
					authors
				ON
					quotes.author_id = authors.id
				INNER JOIN
					categories
				ON
					quotes.category_id = categories.id
				WHERE
					quotes.id = :id
				LIMIT 1 OFFSET 0';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (is_array($row)) {
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
            }

        }
        if (isset($_GET['author_id']) && isset($_GET['category_id'])) {
            $query = 'SELECT
					quotes.id,
					quotes.quote,
					authors.author,
					categories.category
				FROM
					' . $this->table . '
				INNER JOIN
					authors
				ON
					quotes.author_id = authors.id
				INNER JOIN
					categories
				ON
					quotes.category_id = categories.id
				WHERE
					quotes.author_id = :author_id
				AND
					quotes.category_id = :category_id
				ORDER BY quotes.id';

            $this->author_id = $_GET['author_id'];
            $this->category_id = $_GET['category_id'];
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->execute();

            $quotes_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                if (is_array($row)) {
                    $quote_item = array(
                        'id' => $id,
                        'quote' => $quote,
                        'author' => $author,
                        'category' => $category
                    );

                    array_push($quotes_arr, $quote_item);
                }
            }
            return $quotes_arr;
        }


        if (isset($_GET['author_id'])) {
            $query = 'SELECT
					quotes.id,
					quotes.quote,
					authors.author,
                    categories.category
				FROM
					' . $this->table . '
				INNER JOIN
					authors
				ON
					quotes.author_id = authors.id
				INNER JOIN
					categories
				ON
					quotes.category_id = categories.id
				WHERE
					quotes.author_id = :author_id
				ORDER BY quotes.id';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->execute();

            $quotes_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                if (is_array($row)) {
                    $quote_item = array(
                        'id' => $id,
                        'quote' => $quote,
                        'author' => $author,
                        'category' => $category
                    );

                    array_push($quotes_arr, $quote_item);
                }
            }
            return $quotes_arr;
        }

        if (isset($_GET['category_id'])) {
            $query = 'SELECT
					quotes.id,
					quotes.quote,
					authors.author,
					categories.category
				FROM
					' . $this->table . '
				INNER JOIN
					authors
				ON
					quotes.author_id = authors.id
				INNER JOIN
					categories
				ON
					quotes.category_id = categories.id
				WHERE
					quotes.category_id = :category_id
				ORDER BY quotes.id';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->execute();

            $quotes_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                if (is_array($row)) {
                    $quote_item = array(
                        'id' => $id,
                        'quote' => $quote,
                        'author' => $author,
                        'category' => $category
                    );

                    array_push($quotes_arr, $quote_item);
                }
            }
            return $quotes_arr;
        }
    }

    // Create quote

    public function create()
    {
        $query = 'INSERT INTO '
            . $this->table .
            ' (quote, author_id, category_id)
			VALUES (:quote, :author_id, :category_id)
            RETURNING id, quote, author_id, category_id';

        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);


        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->quote = $row['quote'];
            $this->author_id = $row['author'];
            $this->category_id = $row['category'];
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update quote

    public function update()
    {
        $query = 'UPDATE '
            . $this->table .
            ' SET
			    quote = :quote,
				author_id = :author_id,
				category_id = :category_id
			WHERE
				id = :id';

        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        $result = $stmt->rowCount > 0;

        if ($stmt->execute() && $result) {
            return true;

        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete quote

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