<?php
namespace src\classes;

use Exception;
use PDO;
use PDOException;

/**
 * @author Zain Aftab
 * @copyright Zain Aftab - 2021
 *
 * Contains basic database functionality required for developing apis
 */
class Database
{

    /**
     * variable to keep the class instance
     * @var Database
     */
    private static $instance = null;

    protected $_conn;

    private function __construct()
    {
        $this->_conn = new PDO(DBTYPE . ":" . ((defined("DBHOST") ? "host=" . DBHOST : "") . ";") . "dbname=" . DBNAME, DBUSER, DBPASS);
        $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function __clone()
    {}

    /**
     * Returns a created/new Database class instance
     *
     * @return Database
     */
    public static function getInstance()
    {
        self::$instance = empty(self::$instance) ? new static() : self::$instance;
        return self::$instance;
    }

    /**
     * Inserts into database using PDO
     * @param string table table name of destination table
     * @param array data key-value pairs with keys as column names and values to insert data into
     *
     * @return bool true if query executed successfully, otherwise false
     */
    public function insert($table, $data)
    {
        try {
            $columns = array_keys($data);
            $values  = array_values($data);

            $queryc = implode(",", $columns);
            $queryv = ':' . implode(",:", $columns);

            $query = "Insert into $table ($queryc) values ($queryv);";

            $stmt = $this->_conn->prepare($query);

            foreach ($values as $ind => $value) {
                $stmt->bindParam(':' . $columns[$ind], ${$columns[$ind]});
                ${$columns[$ind]} = $value;
            }

            return $stmt->execute();
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Updates data in database using PDO
     * @param string table table name of destination table
     * @param array data key-value pairs with keys as column names for respective values
     * @param array where key-value pairs with keys as column names for respective values
     *
     *
     * @return bool true if query executed successfully, otherwise false
     */
    public function update($table, $data, $where)
    {
        try {
            $columns = array_keys($data);

            $whereComon   = array_intersect_assoc(array_keys($where), $columns);
            $whereColumns = array_keys($where);

            $values      = array_values($data);
            $whereValues = array_values($where);

            $queryc = "";
            $queryw = "";

            foreach ($columns as $ind => $column) {
                $queryc .= $column . '=:' . $column . (!empty($columns[$ind + 1]) ? ',' : "");
            }

            foreach ($whereColumns as $ind => $column) {
                $queryw .= $column . '=:' . $column . (in_array($column, $whereComon) ? $ind : "") . (!empty($whereColumns[$ind + 1]) ? ' and ' : "");
            }

            $query = "Update $table set $queryc where $queryw;";
            $stmt  = $this->_conn->prepare($query);

            foreach ($values as $ind => $value) {
                $stmt->bindParam('' . $columns[$ind], ${$columns[$ind]});
                ${$columns[$ind]} = $value;
            }

            foreach ($whereValues as $ind => $value) {
                $stmt->bindValue(':' . $whereColumns[$ind] . (in_array($whereColumns[$ind], $whereComon) ? $ind : ""), $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
        }
        return false;
    }

    /**
     * Delete data from database using PDO
     * @param string table table name of destination table
     * @param array where key-value pairs with keys as column names for respective values
     *
     * @return bool true if query executed successfully, otherwise false
     */
    public function delete($table, $where)
    {
        try {
            $columns = array_keys($where);
            $values  = array_values($where);

            $queryc = "";

            foreach ($columns as $ind => $column) {
                $queryc .= $column . '=:' . $column . (!empty($columns[$ind + 1]) ? ' and ' : "");
            }

            $query = "Delete from $table where $queryc;";

            $stmt = $this->_conn->prepare($query);

            foreach ($values as $ind => $value) {
                $stmt->bindParam(':' . $columns[$ind], ${$columns[$ind]});
                ${$columns[$ind]} = $value;
            }

            return $stmt->execute();
        } catch (PDOException $e) {
        }
        return false;
    }

    /**
     * Select data from database using PDO
     * @param string table table name of destination table
     * @param array columns indexed array of strings as column names if null will be treated `*` as selecting all columns
     * @param array where key-value pairs with keys as column names for respective values
     * @param array order key-value pair of index as column names and value as ASC/DESC
     * @param integer limit
     *
     * @return array query result is fetched as array and is returned
     */
    public function select($table, $columns, $where, $order, $limit)
    {
        $query = "Select " . (empty($columns) ? '*' : implode(',', $columns)) . " from $table ";

        $whereColumns = array_keys($where);
        $whereValues  = array_values($where);

        $orderColumns = array_keys($order);
        $orderValues  = array_values($order);

        if (!empty($whereColumns)) {
            $query .= 'where ';
            foreach ($whereColumns as $ind => $column) {
                $query .= $column . '=:' . $column . (!empty($whereColumns[$ind + 1]) ? ' and ' : "");
            }
        }

        if (!empty($order)) {
            $query .= " order by ";
            foreach ($orderColumns as $ind => $column) {
                $query .= $column . ' ' . $orderValues[$ind] . (!empty($orderColumns[$ind + 1]) ? ', ' : "");
            }
        }

        if (!empty($limit)) {
            $query .= " limit $limit";
        }

        $stmt = $this->_conn->prepare($query);

        foreach ($whereValues as $ind => $value) {
            $stmt->bindParam(':' . $whereColumns[$ind], ${$whereColumns[$ind]});
            ${$whereColumns[$ind]} = $value;
        }

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Takes a raw query and executes it.
     * @param string $query Query to be executed on db
     * @param mixed $params parameters for query
     *
     * @return array|bool If query is select query then returns result data else returns true if query executed or false if execution failed or exception occurred
     */
    public function preparedQuery($query, $params)
    {
        $stmt = $this->_conn->prepare($query);

        try {
            if ($stmt->execute($params)) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    return true;
                }
            }
        } catch (Exception $e) {

        }
        return false;

    }

    /**
     * Takes a raw query and executes it.
     * @param string query Query to be executed on db
     *
     * @return array|bool If query is select query then returns result data else returns true if query executed or false if execution failed or exception occurred
     */
    public function query($query)
    {
        $stmt = $this->_conn->query($query);
        try {
            if ($stmt->execute()) {
                if ($stmt->columnCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    return true;
                }
            }
        } catch (Exception $e) {

        }
        return false;

    }
}
