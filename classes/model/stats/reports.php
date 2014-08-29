<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_Stats_Reports extends Model_Widget_Decorator {

    public function fetch_data() {
        return array();
    }

    public function hourses($post = NULL) {
        if ($post) {
            $query = DB::select('start_datetime', 'address')
                    ->from('stats')
                    //->where('start_datetime', '>', DB::expr('NOW() - INTERVAL 24 HOUR'))
                    ->where('start_datetime', '>=', $post['start_date'] . ' ' . $post['start_time'])
                    ->and_where('start_datetime', '<=', $post['stop_date'] . ' ' . $post['stop_time'])
                    ->execute()
                    ->as_array();
        } else {
            $query = DB::select('start_datetime', 'address')
                    ->from('stats')
                    ->where('start_datetime', '>', DB::expr('NOW() - INTERVAL 24 HOUR'))
                    ->execute()
                    ->as_array();
        }
        
        
        $array = array(); //Массив для построения графика
        $users = array(); //Массив уникальных IP
        $item = 0;
        
        foreach ($query as $key) {
                        
            $date = new DateTime($key['start_datetime']); // Временная метка RFC
            $date = $date->format('Y-m-d H:00:00'); //Минуты часы обнуляем (для вывода по часам)

            if (empty($array[$date]))
            {
                $array[$date] = array('hour' => $date, 'view' => 1, 'user' => 1);
                $users[] = $key['address']; // Собираем IP во временный массив  
                $array[$date]['view'] = count(array_unique($users));
            }
            else
            {
                
                if (array_key_exists($date, $array))
                {    
                    if (in_array($key['address'],  $users)) 
                    {
                        $array[$date]['view']++;
                    } 
                    else 
                    {
                        
                        $array[$date]['view']++; 
                        $array[$date]['user']++;
                    }
                    
                }
                else
                {    
                    unset($users);
                    $array[$date] = array('hour' => $date, 'view' => 1, 'user' => 0);
                    $users[] = $key['address']; // Собираем IP во временный массив  
                    $array[$date]['view'] = count(array_unique($users));
                }
            }
        }
        
        return $array;
        
        /*
        $stats_hourses = array();
        $users = array();

        foreach ($query as $key)
        {
            
        $date = new DateTime($key['start_datetime']); // Временная метка RFC
        $date = $date->format('Y-m-d H:00:00'); //Минуты часы обнуляем (для вывода по часам)
        
        
         if (!isset($stats_hourses[$date]))
         {
          $stats_hourses[$date] = array(
           'hour' => $date,
           'ip' => array($key['address']),
           'view' => 0, 
           'user' => 0,
          );
         }

         if (!isset($users[$date]))
         {
          $users[$date] = array(
           $key['address']
          );
         }

         if (in_array($key['address'], $stats_hourses[$date]['ip']))
         {
          $stats_hourses[$date]['view'] = 1;
          $stats_hourses[$date]['user'] = 1;
         }
         else
         {
          
          $stats_hourses[$date]['view']++;
          $stats_hourses[$date]['user']++;
         }
        }

        return $stats_hourses;*/
    }

    public function browsers() {

        $query = DB::select('user_agent')
                ->from('stats')
                ->where('start_datetime', '>', DB::expr('NOW() - INTERVAL 24 HOUR'))
                ->execute()
                ->as_array();

        $res = array();

        foreach ($query as $result) {
            if (strpos($result['user_agent'], "Firefox") != false)
                $browser = "Firefox";
            elseif (strpos($result['user_agent'], "Opera") != false)
                $browser = "Opera";
            elseif (strpos($result['user_agent'], "Chrome") != false)
                $browser = "Chrome";
            elseif (strpos($result['user_agent'], "MSIE") != false)
                $browser = "MSIE";
            elseif (strpos($result['user_agent'], "Safari") != false)
                $browser = "Safari";
            elseif (strpos($result['user_agent'], "Trident") != false)
                $browser = "MSIE";
            else
                $browser = "Неизвестный";
            $res[] = $browser;
        }

        $result = array_count_values($res);
        $all = ( 100 / (array_sum($result)) );

        $popular_browsers = array();

        foreach ($result as $key => $value) {
            $popular_browsers[$key] = round($value * $all);
        }

        return $popular_browsers;
    }

}
