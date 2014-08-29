<?php defined('SYSPATH') or die('No direct script access.');
/*
Observer::observe('frontpage_requested', function($plugin) {
        $columns = array(
            'uri',
            'address',
            'start_datetime',
            'user_agent'
        );

        $values = array(
            $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            Request::$client_ip,
            date("Y-m-d H:i:s"),
            $_SERVER["HTTP_USER_AGENT"]
        );

        $query = DB::insert('stats', $columns)
                ->values($values)
                ->execute();
}, $plugin);


<?php defined('SYSPATH') or die('No direct script access.');

Observer::observe('frontpage_requested', function($plugin) {
    try {
        $stats = ORM::factory('orm_stats');
        $stats->uri = $_SERVER['REQUEST_URI'];
        $stats->address = Request::$client_ip;
        $stats->start_datetime = date("Y-m-d H:i:s");
        $stats->user_agent = $_SERVER["HTTP_USER_AGENT"];
        $stats->save();
    } catch (ORM_Validation_Exception $e) {
        Messages::errors($e->errors('stats-records'));
        $this->go_back();
    }
}, $plugin);*/