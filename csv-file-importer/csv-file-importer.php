<?php

/*
Plugin Name: CSV File Importer
Plugin URI: 
Description: Plugin to demonstrate CSV import
Version: 0.1
Author: Jay Dalwadi
Author URI: https://github.com/jaydalwadi78
*/



// Add menu
function plugin_menu() {

   add_menu_page("CSV File Importer", "CSV File Importer","manage_options", "csvfileimporter", "displayList", plugin_dir_url( __FILE__ ) . 'img/jay.jpg');

}
add_action("admin_menu", "plugin_menu");




function displayList(){
   include "displaylist.php";
}


