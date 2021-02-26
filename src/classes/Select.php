<?php

namespace src\classes;

class Select
{

    protected $_table   = "";
    protected $_columns = "";
    protected $_where   = "";
    protected $_groupby = "";
    protected $_orderby = "";
    protected $_limit   = "";
    protected $_join    = "";
    protected $_having  = "";

    protected $_raw    = true;
    protected $_params = array();

    /**
     * @param string $table the table name for query
     * @param array $columns array of strings containing column names
     */
    public function __construct($table, $columns = array(), $raw = true)
    {
        $this->_table   = $table;
        $this->_columns = empty($columns) ? "*" : implode(',', $columns);
        $this->_raw     = $raw;
    }

    /**
     * Adds a WHERE condition to the query by OR.
     *
     * If a value is passed as the second param, it will be quoted
     * and replaced into the condition wherever a question-mark
     * appears. Array values are quoted and comma-separated.
     *
     * <code>
     * // simplest but non-secure
     * $select->where("id = $id");
     *
     * // secure (ID is quoted but matched anyway)
     * $select->where('id = ?', $id);
     *
     * @return Query This Query object
     */
    public function where($condition, $value = null)
    {
        $condition = explode(' ', $condition, 3);
        if (strcmp(strtoupper($condition[1]), 'NOT') == 0) {
            $c = explode(' ', $condition[2], 2);
            $condition[1] .= ' '.$c[0];
            $condition[2] = $c[1];
        }

        $param_name = $condition[0] . rand();

        $this->_where .= (empty($this->_where) ? " " : " and ") . "$condition[0] $condition[1] :$param_name";
        $this->_params[$param_name] = $condition[2];

        return $this;
    }

    /**
     * Adds a WHERE condition to the query by OR.
     *
     * If a value is passed as the second param, it will be quoted
     * and replaced into the condition wherever a question-mark
     * appears. Array values are quoted and comma-separated.
     *
     * <code>
     * // simplest but non-secure
     * $select->where("id = $id");
     *
     * // secure (ID is quoted but matched anyway)
     * $select->where('id = ?', $id);
     * <code>
     *
     * @return Query This Query object
     */
    public function orWhere($condition, $value = null)
    {
        $condition = explode(' ', $condition, 3);
        if (strcmp(strtoupper($condition[1]), 'NOT') == 0) {
            $c = explode(' ', $condition[2], 2);
            $condition[1] .= ' '.$c[0];
            $condition[2] = $c[1];
        }

        $param_name = $condition[0] . rand();
        $this->_where .= (empty($this->_where) ? " " : " or ") . "$condition[0] $condition[1] :$param_name";
        $this->_params[$param_name] = $condition[2];
        return $this;
    }

    /**
     * Adds a row order to the query. Gets the column and direction to order by.
     *
     * @param string $column
     * @param string $direction
     *
     * @return Query This Query object
     */
    public function order($column, $direction)
    {
        $this->_orderby .= (empty($this->_orderby) ? " " : " , ") . "$column $direction";
        return $this;
    }

    /**
     * Add limit clause to query
     *
     * @param integer $limit
     */
    public function limit($limit)
    {
        $this->_limit = is_int(intval($limit)) ? "" . intval($limit) : "";
        return $this;
    }

    public function __toString()
    {
        $query = "Select $this->_columns from $this->_table"
            . (empty($this->_where) ? "" : " where $this->_where")
            . (empty($this->_orderby) ? "" : " order by $this->_orderby")
            . (empty($this->_limit) ? "" : " limit $this->_limit");
        // Logger::getInstance()->info($query);
        return $query;
    }


    public function getQuery(){
        return $this->__toString();
    }

    public function getParams(){
        return $this->_params;
    }
}
