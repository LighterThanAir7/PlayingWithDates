<?php

// Kalendar.php

function TrenutniMjesecKalednar()
{
    $god = date("Y");
    $mj  = date("m");

    echo "\n       Mjesec: " . $mj . " | " . $god . "\n";
    echo str_repeat("-", 30) . "\n";

    $datum1 = $god . "-" . $mj . "-01"; // 2022-11-01

    if(checkdate($mj, 31, $god))
    {
        $datum2 = $god . "-" . $mj . "-31";
    }
    else
    {
        if($mj == 2)
        {
            if(checkdate($mj, 29, $god))
            {
                $datum2 = $god . "-" . $mj . "-29";
            }
            else
            {
                $datum2 = $god . "-" . $mj . "-28";
            }
        }
        else
        {
            $datum2 = $god . "-" . $mj . "-30";
        }
    }

    $datum1_ts = strtotime($datum1);
    $datum2_ts = strtotime($datum2);

    extracted();

    echo "\n";

    for($i=1; $i<=6; $i++)
    {
        for($j=1; $j<=7; $j++)
        {
            if($datum1_ts > $datum2_ts + 3600)
            {
                break;
            }

            $d = date("j", $datum1_ts);
            $n = date("N", $datum1_ts);

            if($n == $j)
            {
                printf("%4s", $d);

                $datum1_ts += (1 * 24 * 60 * 60);
            }
            else
            {
                printf("%4s", " ");
            }
        }
        if ($i == 6)
            echo str_repeat("-", 30) . "\n";
        else
            echo "\n";
    }
}

/**
 * @return void
 */
function extracted(): void
{
    printf("%4s", "PO");
    printf("%4s", "UT");
    printf("%4s", "SR");
    printf("%4s", "CE");
    printf("%4s", "PE");
    printf("%4s", "SU");
    printf("%4s", "NE");
}

?>

