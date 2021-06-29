<?php
const DEBUG = false;
function remove_last(&$arr)
{
    unset($arr[count($arr) - 1]);
}

function table_to_array($table): array
{
    // remove <table> tag
    $start = strpos($table, ">") + 1;
    $table = substr($table, $start);
    $rows = explode('</tr>', $table);
    remove_last($rows); // remove empty row
    
    // remove <tr> tag (</tr> was used as separator earlier)
    for ($i = 0; $i < count($rows); $i++)
    {
        $start = strpos($rows[$i], '<tr>') + 4;
        $rows[$i] = substr($rows[$i], $start);
    }

    // explode
    $rows[0] = explode('</th>', $rows[0]);
    remove_last($rows[0]);
    for ($i = 1; $i < count($rows); $i++)
    {
        $rows[$i] = explode('</td>', $rows[$i]);
        remove_last($rows[$i]); // remove empty column
    }
    // trim
    for ($i = 0; $i < count($rows); $i++)
    {
        for ($j = 0; $j < count($rows[$i]); $j++)
        {
            $start = strpos($rows[$i][$j], '>') + 1;
            $rows[$i][$j] = substr($rows[$i][$j], $start);
        }
    }
    for ($i = 1; $i < count($rows); $i++)
    {
        for ($j = 2; $j < count($rows[$i]); $j++)
        {
            $rows[$i][$j] = str_replace('&nbsp;', 'none', $rows[$i][$j]);
            $rows[$i][$j] = str_replace('<span class="p">', 'P:', $rows[$i][$j]);
            $rows[$i][$j] = str_replace('</span>', '', $rows[$i][$j]);
            $rows[$i][$j] = str_replace('<span class="n">', 'N:', $rows[$i][$j]);
            $rows[$i][$j] = str_replace('</a>', '', $rows[$i][$j]);
            $rows[$i][$j] = str_replace('<br>', '; ', $rows[$i][$j]);
            $rows[$i][$j] = str_replace('<span style="font-size:85%">', '', $rows[$i][$j]);
            while (strpos($rows[$i][$j], '<') !== false)
            {
                $end = strpos($rows[$i][$j], '>') + 1;
                $start = strpos($rows[$i][$j], '<');
                $rows[$i][$j] = substr($rows[$i][$j], 0, $start) . 'S:' . substr($rows[$i][$j], $end);
            }
        }
    }
    if (DEBUG)
    {
        // print
        for ($i = 0; $i < count($rows); $i++)
        {
            echo "i $i: ";
            foreach ($rows[$i] as $el)
            {
                echo "[$el] ";
            }
            echo "<br>";
        }
        echo "cutting end<br>";
    }

    return $rows;
}
?>