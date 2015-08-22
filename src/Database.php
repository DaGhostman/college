<?php
/**
 * @author Dimitar
 * @copyright 2015 Dimitar Dimitrov <daghostman.dimitrov@gmail.com>
 * @package college
 * @license MIT
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Class Database
 * This class is the most minimalistic DBAL (Database Abstraction Layer)
 * possible and allows a simple and elegant way of interacting with the
 * database. It uses a single connection per request and closes it using
 * the built-in PHP Garbage Collector to close the connection.
 */
class Database {
    /**
     * Holds a the database connection resource
     * @var \Mysqli
     */
    private $connection;

    /**
     * This constructor instantiates the connection to the database
     * using the $username, $password and $dbname
     *
     * @param string $username The username to use when connecting
     * @param string $password the corresponding passwowrd
     * @param string $dbname name of the database which we interact with
     */
    public function __construct($username, $password, $dbname)
    {
        $this->connection = new \MySQLi('localhost', $username, $password, $dbname);
        if ($this->connection->connect_errno !== 0) {
            echo sprintf('Connection Error: %s', $this->connection->connect_error);
        }
    }

    public function sanitize(&$value)
    {
        $value = htmlspecialchars($value);

        if (is_array($value)) {
            array_walk($value, function(&$val, $i, $conn) {
                $val = mysqli_real_escape_string($conn, $val);
            }, $this->connection);
        }

        if (is_scalar($value)) {
            $value = mysqli_real_escape_string($this->connection, $value);
        }
    }

    /**
     * Adds a row to the given $table.
     * This method abstract the manual writing of the query in an
     * elegant way and takes a simple approach to sanitize the query
     * values which will be added to their corresponding column
     *
     * @param string $table
     * @param array $fields
     * @return bool True on success and false on failure
     */
    public function insert($table, $fields)
    {

        $this->sanitize($table);
        $this->sanitize($fields);

        $query = sprintf('INSERT INTO %s VALUES ("%s")', (string) $table, (string) implode('", "', array_values($fields)));
        $this->connection->query($query);

        if ($this->connection->errno !== 0) {
            echo sprintf('Error %s.' . PHP_EOL . 'Query: %s', $this->connection->error, $query);
            return false;
        }

        return true;
    }

    /**
     * Deletes the record from $table corresponding to $id
     *
     * @param string $table The name of the table
     * @param int $id The database ID of the row in the database
     * @return bool
     */
    public function delete($table, $id)
    {
        $this->sanitize($table);

        $query = sprintf('DELETE FROM %s WHERE id = %d', (string) $table, (int) $id);
        $this->connection->query($query);

        if ($this->connection->errno !== 0) {
            echo sprintf('Error %s.' . PHP_EOL . 'Query: %s', $this->connection->error, $query);
            return false;
        }

        return true;
    }

    /**
     * Updates a record in the table with corresponding $id
     * and updates all fields defined in $fields (associative
     * array with column => new-value structure.
     *
     * @param string $table The table name
     * @param int $id the ID of the row
     * @param array $fields Assoc array with column name => value pairs
     * @return bool
     */
    public function update($table, $id, $fields)
    {
        $this->sanitize($table);

        $set = array();
        foreach ($fields as $key => $value) {
            $this->sanitize($key);
            $this->sanitize($value);
            $set[] = "{$key} = '{$value}'";
        }

        $query = sprintf(
            'UPDATE %s SET %s WHERE id = %d',
            (string) $table,
            (string) implode(', ', $set),
            (int) $id
        );
        $this->connection->query($query);

        if ($this->connection->errno !== 0) {
            echo sprintf(
                'Error %s.' . PHP_EOL . 'Query: %s', $this->connection->error,
                $query
            );

            return false;
        }

        return true;
    }

    /**
     * Retrieves all records from the $table
     *
     * @param string $table
     * @return bool|array 2 dimensional array with the rows
     */
    public function fetch($table) {
        $this->sanitize($table);

        $query = sprintf('SELECT * FROM %s', $table);
        $result = $this->connection->query($query);

        if ($this->connection->errno !== 0) {
            echo sprintf('Error %s.' . PHP_EOL . 'Query: %s', $this->connection->error, $query);
            return false;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Executes $query directly instead of doing anything with it
     * it does not perform any serializations nor any other
     * operations than error checking and execution.
     *
     * @param string $query
     * @return bool|mysqli_result
     */
    public function custom($query)
    {
        $result = $this->connection->query($query);

        if ($this->connection->errno !== 0) {
            echo sprintf('Error %s.' . PHP_EOL . 'Query: %s', $this->connection->error, $query);
            return false;
        }

        return $result;
    }

    /**
     * Magic method called when the object is no longer referenced
     * within the code.
     *
     * All it does is close the connection when it is not used anymore
     */
    public function __destruct()
    {
        $this->connection->close();
    }
}