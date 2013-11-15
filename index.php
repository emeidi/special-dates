<?php
    $year = 2015;
    $dow = array('Saturday');
    $debug = true;
    $debug = false;
    
    // CONSTANTS
    $dayInSeconds = 24*60*60 + 60;
    //$dowMapper = array();
    
    $init = $year . '-01-01';
    
    $matchingDays = array();
    
    $currUtime = strtotime($init);
    
    if($debug) out('Starting with date ' . date('l, Y-m-d',$currUtime));
    if($debug) out('');
    
    while((int)date('Y',$currUtime) == $year) {
        if($debug) out('Looking at ' . date('l, Y-m-d',$currUtime));
        
        $currDate = getdate($currUtime);
        
        if(!in_array($currDate['weekday'],$dow)) {
            if($debug) out($currDate['weekday'] . ' is not in scope');
            $currUtime += $dayInSeconds;
            if($debug) out('');
            continue;
        }
        
        if($debug) out('Date ' . date('l, Y-m-d',$currUtime) . ' matches');
        
        $matchingDates[] = $currDate;
        $currUtime += $dayInSeconds;
        
        if($debug) out('');
    }
    
    out('Found ' . count($matchingDates) . ' matching dates');
    //out('');
    
    // ==============================================================
    outHeader('Dates with twins or symmetric numbers');
    
    foreach($matchingDates as $matchingDate) {
        $day = date('d',$matchingDate[0]);
        $month = date('m',$matchingDate[0]);
        
        if($debug) out('Looking at ' . date('l, Y-m-d',$matchingDate[0]));
        
        if($debug) out('+++ Checking for twins');
        
        if($day == $month) {
            out('Twins found in date ' . date('l, Y-m-d',$matchingDate[0]));
            //out('');
        }
        
        if($debug) out('+++ Checking for reversed');
        
        $dayReversed = implode(null,array_reverse(str_split($day)));
        if($dayReversed == $month) {
            out('Symmetry found in date ' . date('l, Y-m-d',$matchingDate[0]));
            //out('');
        }
    }
    
    // ==============================================================
    // Now measure date entropy
    $measure = array();
    foreach($matchingDates as $matchingDate) {
        $dateYYYY = date('Ymd',$matchingDate[0]);
        $dateReadable = date('l, Y-m-d',$matchingDate[0]);
        $dateYY = date('ymd',$matchingDate[0]);
        
        //out($dateReadable);
        
        $checks = array('Year as 4 digits' => $dateYYYY, 'Year as 2 digits' => $dateYY);
        
        foreach($checks as $key=>$date) {
            $chars = str_split($date);

            $map = array();
            foreach($chars as $char) {
                $char = (int)$char;
                if(!isset($map[$char])) {
                    $map[$char] = 0;
                }

                $map[$char] += 1;
            }

            $numDifferentChars = count($map);
            
            //if($debug) out($numDifferentChars . ' different chars');

            $measure[$key][$numDifferentChars][] = $matchingDate;
        }
    }
    
    // ==============================================================
    outHeader('Dates with a lot of similar numbers');
    
    $dateStrings = array('Year as 4 digits' => 'l, Y-m-d','Year as 2 digits' => 'l, y-m-d');
    foreach($measure as $type=>$elements) {
        out($type);
        out(str_repeat('-',strlen($type)));
        
        ksort($elements);
        
        $dateString = $dateStrings[$type];
        
        $count = 0;
        foreach($elements as $numDifferentChars=>$dates) {
            if($count > 0) {
                break;
            }
            
            foreach($dates as $element) {
                $date = date($dateString,$element[0]);
                out($numDifferentChars . ' unique digits: ' . $date);
            }
            
            $count++;
        }
        
        out('');
    }
    
    outHeader('All ' . count($matchingDates) . ' matching dates');
    
    foreach($matchingDates as $matchingDate) {
        out(date('l, Y-m-d',$matchingDate[0]));
    }
    
    function out($msg = null) {
        print($msg . "\n");
    }
    
    function outHeader($header = null) {
        out('');
        out($header);
        out(str_repeat('=',strlen($header)));
        //out('');
    }
?>