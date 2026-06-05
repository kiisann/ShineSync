<?php
// app/core/Model.php — Base Model
class Model
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
