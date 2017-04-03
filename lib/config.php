<?php
/*
 * Defines configuration variables for our site
 */
class config{
  public $url_local  = '/s/bach/g/under/mjrerle/public_html/p2';
  public $url_public = '/s/bach/g/under/mjrerle/public_html/p2';
  public $base_url = '';  /* Selected below based upon server */
  public $site_name = "CT 310: Project 2 with mjrerle and";
  public $site_lmod = "3/19/17 6:00PM";
  public $matience = false;
  public $session_name = "p2_with_mjrerle_and_";
}

$config = new config();

/* Select the proper base_url for development vs. public server */
$test_local_p = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', "::1")));
$config->base_url = $test_local_p ? $config->url_local : $config->url_public;
