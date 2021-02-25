<?php

namespace src\controllers;

use src\classes\Database;
use src\classes\Logger;
use src\classes\Select;

class ProductController
{

    public static function indexAction()
    {
        $db     = Database::getInstance();
        $logger = Logger::getInstance();

        $query = new Select("first_table");
        $logger->info($query->where("name like z%")->order('name', 'asc')->limit("1")->__toString());
        $result = $db->preparedQuery($query->getQuery(), $query->getParams());
        if (!empty($result)) {
            return array('status' => "success", 'data' => $result);
        } else {
            return array('status' => 'failed');
        }

    }

    public static function rawqueryAction()
    {
        $db     = Database::getInstance();
        $logger = Logger::getInstance();

        $result = $db->query("Select * from first_table where name like 'z%' order by name desc");
        $logger->info("Select * from first_table where name like 'z%' order by name desc", $result);
        if (!empty($result)) {
            return array('status' => "success", 'data' => $result);
        } else {
            return array('status' => 'failed');
        }
    }

    public static function deleteAction()
    {
        $db     = Database::getInstance();
        $logger = Logger::getInstance();

        $row    = array('name' => 'username');
        $delete = $db->delete("first_table", $row);
        $logger->debug("Deleting row from db => " . $delete, $row);

        if ($delete) {
            return array('status' => "success");
        } else {
            return array('status' => 'failed');
        }
    }

    public static function updateAction()
    {
        $db     = Database::getInstance();
        $logger = Logger::getInstance();

        $row     = array('name' => 'username', 'email' => 'n' . time() . '@gmail.com');
        $updated = $db->update("first_table", $row, array('name' => 'name1613423035'));
        $logger->debug("Updating a row in db => " . $updated, $row);

        if ($updated) {
            return array('status' => "success");
        } else {
            return array('status' => 'failed');
        }
    }

    public static function addAction()
    {
        $db     = Database::getInstance();
        $logger = Logger::getInstance();

        $row = array('name' => 'name' . time(), 'email' => time() . '@.com');

        $inserted = $db->insert("first_table", $row);
        $logger->debug("Inserting a row into db => " . $inserted, $row);
        if ($inserted) {
            return array('status' => "success");
        } else {
            return array('status' => 'failed');
        }
    }

    public static function listAction()
    {
        $db     = Database::getInstance();
        $logger = Logger::getInstance();

        $selected = $db->select("first_table", null, array('email' => '1613423153@.com'), array('name' => 'desc'), 1);
        $logger->info("listing from db", $selected);

        if (!empty($selected)) {
            return array('status' => "success", 'data' => $selected);
        } else {
            return array('status' => 'failed');
        }
    }

}
