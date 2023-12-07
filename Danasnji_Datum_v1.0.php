<?php

include "Kalendar.php";
//$fp = fopen("C:\Beni\OneDrive - Visoko uciliste Algebra\Benjamin_Babić_1RM2\Zad22. Semestar\Uvod u OOP\Zadaci - 1.Semestar_OOP_Pristup\Tablica_Ime_Prez_Datum_R.txt", "a+");

class Datum {
    public string $danasnji_datum;
    public array $datum_rodjenja;
    public bool $isDateOfBirthAssigned = false;
    public $Separator_Datuma;

    // ===========| Ispisivanje Današnjeg Datuma |===========
    public function IspisDatuma_OdabirFormata() {
        echo <<<EOF
        \n=========| Ispis Današnjeg Datuma |==========
        |=> Odaberi broj željenog prikaza formata datuma:
        (1) - d.M.yyyy.
        (2) - d.M.yy.
        (3) - d. M. yyyy.
        (4) - dd.MM.yyyy.
        (5) - d. M. yy.
        (6) - dd.MM.yy.
        (7) - dd. MM. yy.
        (8) - yyyy-MM-dd\n\n
        EOF;
        while (true) {
            $format_datuma = readline("|=> Vaš odabir: ");
            if ($format_datuma >= 1 && $format_datuma <= 8) {
                break;
            } else {
                echo "|=> Pogrešan Unos Opcije, Odaberite Valjanu Opciju: \n";
            }
        }
        return $format_datuma;
    }

    public function IspisDatuma_OdabirSeparatora()
    {
        echo <<<EOF
        \n|=> Odaberi željeni separator:
        (1) .
        (2) |
        (3) /
        (4) -\n\n
        EOF;

        while (true) {
            $separator_datuma = readline("|=> Vaš odabir: ");
            if ($separator_datuma == 1) {
                $separator_datuma = ".";
                break;
            } elseif ($separator_datuma == 2) {
                $separator_datuma = "|";
                break;
            } elseif ($separator_datuma == 3) {
                $separator_datuma = "/";
                break;
            } elseif ($separator_datuma == 4) {
                $separator_datuma = "-";
                break;
            } else
                echo "|=> Pogrešan Unos Separatora, Odaberite Valjanu Opciju: \n";
        }
        $this->Separator_Datuma = $separator_datuma;
        return $separator_datuma;
    }

    public function IspisDatuma_SeparatorNaKraju () {
        while (true) {
            $separator_na_kraju = readline("|=> Želite li separator na kraju datuma? (y/n) => ");
            if ($separator_na_kraju == 'y' || $separator_na_kraju == 'Y') {
                $separator_na_kraju = $this->Separator_Datuma;
                echo "\n|=> Treutni Datum: ";
                break;
            } else if ($separator_na_kraju == "n" || $separator_na_kraju == "N"){
                $separator_na_kraju = "";
                echo "\n|=> Treutni Datum: ";
                break;
            } else {
                echo "|=> Pogrešan Unos, Unesite 'y' za nastavak ili 'n' za kraj: \n";
            }
        }
        return $separator_na_kraju;
    }
    // ======================================================
    public function Meni($br_akcije): string
    {
        switch ($br_akcije) {
            case 1:
                echo $this->getDanasnjiDatum();
                break;
            case 2:
                TrenutniMjesecKalednar();
                break;
            case 3:
                $this->setDatumRodjenja();
                echo $this->getGodine();
                break;
            case 4:
                echo $this->getDaniProtekloOdRodjenja();
                break;
            case 5:
                echo "\n===========================|  Razlika između SADA i Željenog Datuma  |============================\n";
                echo "NAPOMENA: Godine je moguće unositi i kao zadnja dva broja | 1-69 => 2001-2069 | 70-99 => 1970-1999\n";
                $provjerena_lista = $this->ProvjeriUpis();
                $trazeni_datum = strtotime("{$provjerena_lista[0]}-{$provjerena_lista[1]}-{$provjerena_lista[2]} {$provjerena_lista[3]}:{$provjerena_lista[4]}");
                echo $this->RazlikaDatuma($trazeni_datum);
                break;
            default:
                echo "|=> Ne postoji takva Akcija, Ponovi Unos!\n";
                $meni_odabir = readline("|=> Broj Akcije: ");
                $this->Meni($meni_odabir);
        }
        return 0;
    }

    public function getDatumRodjenja(): array
    {
        return $this->datum_rodjenja;
    }

    public function je_li_UpisanBroj($array_brojeva): bool
    {
        $check = 0;
        foreach ($array_brojeva as $broj){
            if (!is_numeric($broj)) {
                $check++;
                break;
            }
        }
        if ($check == 0){
            return true;
        } else {
            return false;
        }
    }

    public function ProvjeriUpis (): array
    {
        $lista_podataka = array();
        while (true) {
            $DanMjesecGodina = $this->UpisDatuma();
            if (!$this->je_li_UpisanBroj($DanMjesecGodina) ||
                !$this->ProvjeraIspravnogDatuma($DanMjesecGodina[1], $DanMjesecGodina[0], $DanMjesecGodina[2])) {
                echo "\n|=> Takav datum Ne Postoji, Ponovite Unos! \n\n";
            } else {
                array_push($lista_podataka, $DanMjesecGodina[2], $DanMjesecGodina[1], $DanMjesecGodina[0]);
                while (true) {
                    echo "\nVrijeme Unesite u Formatu HH:MM\n";
                    $sat = readline("|=> Učitaj Sate: ");
                    if ($sat < 10){
                        $sat = "0" . $sat;
                    }
                    $min = readline("|=> Učitaj Minute: ");
                    if ($min < 10){
                        $min = "0" . $min;
                    }
                    $foo = "{$sat}:{$min}";
                    if (preg_match("/^(?:Zad22[0-3]|[01][0-9]):[0-5][0-9]$/", $foo) == 0){
                        echo "\n|=> Neispravan Unos Vremena, Ponovite Unos! \n";
                    } else {
                        array_push($lista_podataka, $sat, $min);
                        break;
                    }
                }
                break;
            }
        }
        return $lista_podataka;
    }

    public function UpisDatuma() {
        echo "===| Upiši Podatke o Datumu Rođenja |===\n";
        $day = (int)readline("|=> Unesi Dan: ");
        $mjesec = (int)readline("|=> Unesi Mjesec: ");
        $godina = (int)readline("|=> Unesi Godinu: ");
        return array($day, $mjesec, $godina);
    }

    public function setDatumRodjenja(): array
    {
        while (true) {
            $Datum_Data = $this->UpisDatuma();
            if ($this->ProvjeraIspravnogDatuma($Datum_Data[1], $Datum_Data[0], $Datum_Data[2]) == false) {
                echo "\n|=> Takav datum Ne Postoji, Ponovite Unos! \n\n";
            } else {
                $this->isDateOfBirthAssigned = true;
                $this->datum_rodjenja = array($Datum_Data[0], $Datum_Data[1], $Datum_Data[2]);
                break;
            }
        }
        return $this->datum_rodjenja;
    }

    public function getDanasnjiDatum() {
        $format = $this->IspisDatuma_OdabirFormata();
        $separator = $this->IspisDatuma_OdabirSeparatora();
        $sep_kraj = $this->IspisDatuma_SeparatorNaKraju();
        switch ($format) {
            case 1:
                $this->danasnji_datum = date("j{$separator}n{$separator}Y$sep_kraj");
                break;
            case 2:
                $this->danasnji_datum = date("j{$separator}n{$separator}y$sep_kraj");
                break;
            case 3:
                $this->danasnji_datum = date("j$separator n$separator Y$sep_kraj");
                break;
            case 4:
                $this->danasnji_datum = date("d{$separator}m{$separator}Y$sep_kraj");
                break;
            case 5:
                $this->danasnji_datum = date("j$separator n$separator y$sep_kraj");
                break;
            case 6:
                $this->danasnji_datum = date("d{$separator}m{$separator}y$sep_kraj");
                break;
            case 7:
                $this->danasnji_datum = date("d$separator m$separator y$sep_kraj");
                break;
            case 8:
                $this->danasnji_datum = date("Y{$separator}m{$separator}d");
                break;
        }
        return $this->danasnji_datum;
    }

    public function ProvjeraIspravnogDatuma($month, $day, $year): bool
    {
        return checkdate($month, $day, $year);
    }

    public function getGodine(): string
    {
        $dan_trenutni = date("j");
        $mj_trenutni  = date("n");
        $god_trenutna = date("Y");

        $dan_rodjenja = $this->getDatumRodjenja()[0];
        $mj_rodjenja  = $this->getDatumRodjenja()[1];
        $god_rodjenja = $this->getDatumRodjenja()[2];

        if ($dan_rodjenja > $dan_trenutni && $mj_rodjenja > $mj_trenutni && $god_rodjenja > $god_trenutna) {
            return "\n|=> Osoba se još nije ni Rodila";
        } else {
            if ($mj_trenutni <= $mj_rodjenja && $dan_trenutni < $dan_rodjenja) {
                $god_trenutna--;
            }
            $ispis = $god_trenutna - $this->getDatumRodjenja()[2] . " god\n";
            return "\n|=> Osoba za uneseni datum ima " . $ispis;
        }
    }

    public function getDaniProtekloOdRodjenja(): string
    {
        while (true) {
            $trenutni = strtotime("now"). "\n";
            $date_data = array();
            $DayMonthYear = $this->UpisDatuma();
            array_push($date_data, $DayMonthYear[0], $DayMonthYear[1], $DayMonthYear[2]);
            if ($this->ProvjeraIspravnogDatuma($DayMonthYear[1], $DayMonthYear[0], $DayMonthYear[2]) == false) {
                echo "\n|=> Takav datum Ne Postoji, Ponovite Unos! \n\n";
            } else {
                $datum_rodjenja = strtotime("{$DayMonthYear[0]}-{$DayMonthYear[1]}-{$DayMonthYear[2]}") . "\n";
                if ($trenutni < $datum_rodjenja) {
                    $ispis = "\n|=> Osoba se još nije ni Rodila";
                } else {
                    $proteklo_dana = abs(($trenutni - $datum_rodjenja));
                    $proteklo_dana /= (60*60*24); // sekunde * minute * sati
                    $ispis = "|=> Proteklo Dana od rođenja: " . floor($proteklo_dana) . "\n";
                }
                break;
            }
        }
        return $ispis;
    }

    public function RazlikaDatuma($doTogDatuma): string
    {
        //$danasnji_datum = strtotime("Y-m-d H:i:s");
        $danasnji_datum = strtotime("now");
        $diff = abs($danasnji_datum - $doTogDatuma);

        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
        $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
        //$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));

        $rezultat = sprintf("%d years, %d months, %d days, %d hours, " . "%d minutes", $years, $months, $days, $hours, $minutes);

        if ($danasnji_datum > $doTogDatuma) {
            return "|=> Od danasnjeg datuma proslo je: " . $rezultat . "\n";
        } else {
            return "|=> Do tog datuma ima:  " . $rezultat . "\n";
        }
    }
}

// ==========================| GLAVNI PROGRAM |==========================
// ----------------------------------------------------------------------

$datum = new Datum();

while (true) {
    echo <<<EOF
    |=> Odaberi Akciju:
    (1) - Ispis Današnjeg Datuma
    (2) - Ispis Kalendara za Trenutni Datum
    (3) - Ispis Godina starosti od Trenutka Rođenja
    (4) - Ispis Dana proteklih od Rođenja
    (5) - Vrijeme do Željenog Datuma gledano od Ovog Trenutka\n\n
    EOF;
    $meni_odabir = readline("|=> Broj Akcije: ");
    $datum->Meni($meni_odabir);
    echo "\n";
    while (true) {
        $nastavak = readline("|=> Želite li nastaviti s programom? (y/n) => ");
        if ($nastavak == 'n' || $nastavak == 'N'){
            echo "\n===| Kraj Programa |===";
            exit();
        } else if ($nastavak == 'y' || $nastavak == 'Y'){
            echo "\n";
            break;
        } else {
            echo "|=> Pogrešan Unos, Unesite 'y' za nastavak ili 'n' za kraj: \n";
        }
    }
}

// TODO
// Popravi getDaniProtekloOdRodjenja Funkciju
// Probaj napravit bazu podataka u stilu txt datotetke koja će se moći čitati i u nju pisati
// Napravi bar 20-ak podataka u njoj ovako:
// IME | PREZIME | DATUM ROĐENJA (Format - dd.mm.yyyy)
// Izvuci podatke van i ispiši ovdje, ili upisuj podatke unutra kroz program
// Napravi filtre za osobe mlađe od X godina (koristeći njihov datum i funkciju za trenutni datum)
// Izvuci sve osobe kojima ime počinje na određeno slovo 

?>