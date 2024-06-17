<?php 
function convertInTimeAgo($time) {
    $current_time = time();
    
    $time = strtotime($time);
    
    $time_in_datetime = new DateTime("@$time");
    
    $interval = $time_in_datetime->diff(new DateTime("@$current_time"));
    
    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } elseif ($interval->s > 0) {
        return 'a few seconds ago';
    } else {
        return 'just now';
    }
}

function convertInDate($time) {
    $time = strtotime($time);

    $time_in_datetime = new DateTime("@$time");
    
    return $time_in_datetime->format('Y-m-d');
}
?>