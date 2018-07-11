<?php
$state = '';
$index = 0;
$f = null;
$toc = [];
foreach (file('all.txt') as $key => $line) {
    $ln = trim($line);
    if (preg_match('/^-+$/', $ln)) {
        $state = 'title';
        continue;
    }
    if (strlen($ln) == 0) {
        continue;
    }
    if ($state == 'title') {
        if ($f) fclose($f);
        $fl = str_pad(strval($index++), 4, '0', STR_PAD_LEFT);
        $file = "$fl.md";
        echo $file,"\t$line";
        $toc[] = [$ln, $fl];
        $f = fopen($file, "w");
        fwrite($f, "## $ln\n");
        $state = '';
        continue;
    }
    if ($f) fwrite($f, qudaoban($ln)."\n\n");
}
if ($f) fclose($f);

$f = fopen("toc.md", "w");
foreach ($toc as $key => $v) {
    fwrite($f, "- [$v[0]]($v[1])\n");
}
fclose($f);

function qudaoban($txt) {
    $txt = preg_replace("/<strong>[^<]+<\/strong>/", '', $txt);
    $txt = preg_replace("/（[^（]*wwW[^（]*）/i", '', $txt);
    $txt = preg_replace("/\[[^\[]*(wwW|cc)[^\[]*\]/i", '', $txt);
    $txt = str_replace("（wwW.80txt.com 无弹窗广告）", "", $txt);
    return $txt;
}