<?php
//setcookie("myCookie2", 'test2', time() + (86400 * 30), '/');

require('simple_html_dom.php');
include('cookies.php');
require('plans.php');
$plans = get_plans();

set_cookie("test3a", 'test3a');
if (isset($_GET['class']))
{
    $class = $_GET['class'];
    echo "<!-- get [$class] -->";
    set_cookie("test3b", 'test3b');
    if (get_cookie('cookie_accept') !== false)
    {
        echo '<!-- cookies accepted (a) -->';
        set_cookie('class_c', "$class");
        set_cookie("test3c", 'test3c');
        echo '<!-- cookies set -->';
    }
}
else
{
    $class = '1A'; // default class
    if (get_cookie('cookie_accept') !== false)
    {
        $cookie_class = get_cookie('class_c');
        echo '<!-- cookies accepted (b) [' . $cookie_class . '] -->';
        if ($cookie_class !== false)
            $class = $cookie_class;
    }
    else
    {
        echo '<!-- cookies not accepted -->';
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wspaniały plan</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/table.css">
    <link rel="icon" href="img/staszic-beztekstu.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitter&display=swap" rel="stylesheet">
</head>
<body>
    <div id="header">
        <script src="js/style_var.js"></script>
        <script src="js/header.js"></script>
        <a href="https://lo1.lublin.eu/staszic/" title="strona szkoły"><img id="logo-img" src="img/staszic-650px.png" alt="logo szkoły"></a>
        <h1 id="main-h1">Ulepszony plan | <?php echo "klasa: $class<br>"; ?></h1>
    </div>

    <div id="menu" class="panel">
        <div id="menu-slider">
            <h2 id="classes-h2">Klasy :</h2>
            <ul>
            <?php
                // create links
                foreach($plans as $k => $i)
                    echo "<li><a href='?class=$k' class='plan-a'>$k</a><br></li>";
            ?>
            </ul>
        </div>
    </div>

    <div id="schedule" class="panel">
        <h2>Plan :</h2>
    <?php
        $school_site = file_get_html($plans[$class]);
        $original_table = $school_site->find('table[class=tabela]', 0);
   
        require('cutter.php');
        $rows = table_to_array($original_table);
        // calculate break times
        $breaks = array();
        for ($i = 2; $i < count($rows); $i++)
        {
            $break_begin = substr($rows[$i - 1][1], 6, 5);
            $break_end = substr($rows[$i][1], 0, 5);
            $break_dur = (strtotime($break_end) - strtotime($break_begin)) / 60; // duration of break in minutes
            $breaks[] = $break_dur;
        }
        
        // add breaks
        for ($i = 0, $j = 0; $j < count($breaks); $i++, $j++)
        {
            array_splice($rows, $i + 2, 0, array(array($breaks[$j])));
            $i++;
        }
        $rows = array_values($rows);

        // print table
        echo '<table>';
        // headers
        echo '<tr>';
        echo '<th id="first-header">' . $rows[0][0] . '</th>';
        echo '<th id="second-header">' . $rows[0][1] . '</th>';
        for ($i = 2; $i < count($rows[0]); $i++)
            echo '<th class="header">' . $rows[0][$i] . '</th>';
        echo '</tr>';

        const BREAK_SIZE = 3; // px per minute
        for ($i = 1; $i < count($rows); $i++)
        {
            echo '<tr>';
            if ($i % 2 == 1)
            {
                // LESSON

                echo '<td class="first-col-td">' . $rows[$i][0] . '</td>'; // first column
                $rows[$i][1] = str_replace('-', ' - ', $rows[$i][1]); // add spaces
                echo '<td class="second-col-td">' . $rows[$i][1] . '</td>'; // second column

                for ($j = 2; $j < count($rows[$i]); $j++)
                {
                    if ($rows[$i][$j] != 'none')
                    {
                        echo '<td class="lesson-td" style="height: 80px;">';
                        $rows[$i][$j] = explode(';', $rows[$i][$j]); // divide groups
                        foreach ($rows[$i][$j] as $lesson)
                        {
                            $lesson = trim($lesson);
                            $lesson = explode(' ', $lesson);
                            
                            // cut prefixes
                            $subject = substr($lesson[0], 2); // first element
                            $teacher = substr($lesson[count($lesson)-2], 2); // second to last element
                            $classroom = substr($lesson[count($lesson)-1], 2); // last element
                            
                            // expanding names
                            $subject = str_replace('r_', 'roz. ', $subject);
                            $subject = str_replace('u_', 'u. ', $subject);
                            $subject = str_replace('j.', 'j. ', $subject);
                            $subject = str_replace('hist.i', 'historia i społeńczesto', $subject);
                            $subject = str_replace('Edu.eko.', 'edukacja ekologiczna', $subject);
                            $subject = str_replace('El_mech.', 'elementy mechaniki', $subject);
                            $subject = str_replace('El.', 'elementy ekonomii', $subject);
                            $subject = str_replace('informat.', 'informatyka', $subject);
                            $subject = str_replace('wos', 'wiedza o społeczństwie', $subject);
                            $subject = str_replace('e_dla_bezp', 'edu. dla bezpie.', $subject);
                            $subject = str_replace('przedsięb.', 'pod. przedsiębiorczości', $subject);
                            $subject = str_replace('zaj.', 'godzina wychowawcza', $subject);
                            $subject = str_replace('doradz.', 'doradztwo zawodowe', $subject);
                            $subject = str_replace('ekon.w', 'ekonomia', $subject);
                            
                            echo '<span class="group">' .
                                '<span class="subject">' . $subject . '</span><br>' .
                                '<span class="teacher">' . $teacher . '</span> '.
                                '<span class="classroom">[' . $classroom . ']</span>'.
                                '</span><br>';
                        } 
                    }
                    else
                    {
                        echo '<td class="none-td"><span class="none"> - </span>';
                    }
                    echo '</td>';
                }
            }
            else
            {
                // BREAK
                echo '<td class="break-td" colspan="7" style="height: ' . BREAK_SIZE * $rows[$i][0]. 'px">' . $rows[$i][0]  . ' min</td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        $when = $school_site->find('td[align=left]', 0);
        echo '<p>' . $when->plaintext . '</p>';
    ?>
    </div>
    <div id="footer">
        <p>Strona szkoły I LO im. Stanisława Staszica <a href="https://lo1.lublin.eu/">www.lo1.lublin.eu</a>. Plan pobrano autommatycznie z <a href="https://lo1.lublin.eu/plan/">www.lo1.lublin.eu/plan</a>.</p>
        <p>Cały kod strony można znaleźć na: <a href="https://github.com/Kowalskiexe/lo1-plan" target="_blank">www.github.com/kowalskiexe/lo1-plan</a>.</p>
        <p>
            by Maciej Kowalski<br>
            <a href="mailto:kowalski.exe@gmail.com">kowalski.exe@gmail.com</a><br>
        </p>
    </div>

    <?php
    if (get_cookie('cookie_accept') === false)
        echo 
            '<div id="cookies-notice">
                <script src="js/cookies.js"></script>
                <script src="js/cookies-notice.js"></script>
                <div id="notice-content">
                    <p>Ta strona używa plików <a href="https://pl.wikipedia.org/wiki/HTTP_cookie" target="_blank">cookies</a>.</p>
                    <button onclick="CookiesNotice.accept()">Akceptuję</button><button onclick="CookiesNotice.decline()">Później</button>
                </div>
            </div>';
    ?>

</body>
</html>