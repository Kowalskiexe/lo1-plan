<?php
const SCHOOL_URL = 'https://lo1.lublin.eu/plan/';
function get_plans(): array
{
    $school_plans = file_get_html('https://lo1.lublin.eu/plan/lista.html');
    $plans = array();
        foreach($school_plans->find('ul', 0)->find('a') as $link)
            $plans[$link->plaintext] = SCHOOL_URL . $link->href;
    return $plans;
}