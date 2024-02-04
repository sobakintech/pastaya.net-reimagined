<?php
$secret = '8501f9c2-75ba-4230-8188-51037c4da102';
$expires = 9_999_999_999;
$token = base64_urldecode(hash('sha256', $secret . $secret));
//$res = Http::get('https://geometrydashfiles.b-cdn.net/sfx/sfxLibrary.dat?token=' . $token . '&expires=' . $expires)->body();
$res = Http::get('https://pastaya.net/files/sfx/sfxLibrary.dat')->body();
$res = mb_convert_encoding($res, 'UTF-8', 'UTF-8');;
$res = base64_urldecode($res);
$res = zlib_decode($res);
$res = explode('|', $res);
$library = [];
for ($i = 0; $i < count($res); $i++) {
    $res[$i] = explode(';', $res[$i]);
    array_pop($res[$i]); // Remove trailing ';'
    if ($i === 0) $library['version'] = (int)explode(',', $res[0][0])[1];
    for ($j = 1; $j < count($res[$i]); $j++) {
        $bits = explode(',', $res[$i][$j]);
        switch ($i) {
            case 0: // File/Folder
                if ($bits[2]) {
                    $library['folders'][(int)$bits[0]] = [
                        'name' => $bits[1],
                        'parent' => (int)$bits[3],
                    ];
                } else {
                    $library['files'][(int)$bits[0]] = [
                        'name' => $bits[1],
                        'parent' => (int)$bits[3],
                        'bytes' => (int)$bits[4],
                        'milliseconds' => (int)$bits[5],
                    ];
                }
                break;
            case 1: // Credit
                $library['credits'][] = [
                    'name' => $bits[0],
                    'website' => $bits[1],
                ];
                break;
        }
    }
}
