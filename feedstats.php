<?php
/*
Plugin Name: LSFeedStats
Plugin URI: http://leonid.shevtsov.me
Description: Feed statistics by Leonid Shevtsov
Version: 0.1
Author: Leonid Shevtsov
Author URI: http://leonid.shevtsov.me

    Copyright 2009  Leonid Shevtsov  (email : leonid@shevtsov.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$ls_feedstats_visits_table_name = "{$wpdb->prefix}feedvisits";

function ls_feedstats_install()
{
  global $wpdb;
  global $ls_feedstats_visits_table_name;
  
  if ($wpdb->get_var("SHOW TABLES LIKE '#{$ls_feedstats_visits_table_name}'") != $ls_feedstats_visits_table_name) {
    $wpdb->query("CREATE TABLE {$ls_feedstats_visits_table_name} (
      id int NOT NULL AUTO_INCREMENT,
      time int NOT NULL,
      ip varchar(15) NOT NULL,
      referer varchar(255) NOT NULL,
      useragent varchar(255) NOT NULL,
      UNIQUE KEY id (id)
    )");
  }
}


// Client IP, considering proxy masquerading
function ls_feedstats_get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip=$_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}


//The method that collects statistics
function ls_feedstats_visit()
{
  global $wpdb;
  global $ls_feedstats_visits_table_name;

  $wpdb->insert($ls_feedstats_visits_table_name, array(
    'time' => time(),
    'ip' => ls_feedstats_get_client_ip(),
    'referer'=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
    'useragent'=> isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
   ), array('%d','%s','%s','%s'));
}

# Hooks
register_activation_hook(__FILE__,'ls_feedstats_install');
add_action('do_feed_rss2', 'ls_feedstats_visit', 1, false);
