<?php

require 'models/Model.php';

class Category extends Model
{

    public function __construct()
    {
        parent::__construct('categories');

    }

}