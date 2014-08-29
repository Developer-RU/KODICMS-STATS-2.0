<?php

defined('SYSPATH') or die('No direct script access.');

Observer::observe('frontpage_requested', function($plugin)
{
    $res = DB::select('*')
        ->from('stats')
        ->where('start_datetime', '=', date("Y-m-d H:i:00"))
        ->and_where('referrer', '=', $_SERVER['HTTP_REFERER'])
        ->and_where('address', '=', Request::$client_ip)
        ->limit('1')
        ->order_by('id', 'ASC')
        ->execute()
        ->as_array();
    
    if ( $res['referrer'] !== $_SERVER['HTTP_REFERER'] )
    {
        try 
        {
            
            $uri = Request::$host . $_SERVER['REQUEST_URI'];
            $address = Request::$client_ip;
            $referrer = $_SERVER['HTTP_REFERER'];
            $start_datetime = date("Y-m-d H:i:00");
            $user_agent = Request::$user_agent;
           
            $query = DB::insert('stats', array('uri', 'address', 'referrer', 'start_datetime', 'user_agent'))
                ->values(array($uri, $address, $referrer, $start_datetime, $user_agent ))->execute();
            
        } 
        catch (ORM_Validation_Exception $e) 
        {
            Messages::errors($e->errors('stats_records'));
            $this->go_back();
        }
    }
}, $plugin);
