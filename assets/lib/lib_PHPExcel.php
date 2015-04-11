<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $config['path'].'lib/excel');

/** PHPExcel_Cell */
require_once 'PHPExcel.php';

/** PHPExcel_IOFactory */
require_once 'PHPExcel/IOFactory.php';
