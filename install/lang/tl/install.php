<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Automatically generated strings for Moodle installer
 *
 * Do not edit this file manually! It contains just a subset of strings
 * needed during the very first steps of installation. This file was
 * generated automatically by export-installer.php (which is part of AMOS
 * {@link http://docs.moodle.org/en/Development:Languages/AMOS}) using the
 * list of strings defined in /install/stringnames.txt.
 *
 * @package   installer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['admindirname'] = 'Pang-Admin na Bugsok';
$string['chooselanguagehead'] = 'Pumilì ng wika';
$string['chooselanguagesub'] = 'Pumili po ng wika para sa pagluluklok LAMANG.  Sa mga susunod na iskrin ay makakapili ka ng wika para sa site o tagagamit.';
$string['dataroot'] = 'Bugsok ng Datos';
$string['dbprefix'] = 'Unlapi ng mga teybol';
$string['dirroot'] = 'Bugsok ng Moodle';
$string['installation'] = 'Pagluklok';
$string['langdownloaderror'] = 'Ikinalulungkot namin na ang wikang "{$a}" ay hindi nailuklok. Ang kabuuan ng pagluluklok ay itutuloy sa Ingles.';
$string['memorylimithelp'] = '<p>Ang memory limit ng PHP para sa server mo ay kasalukuyang nakatakda sa {$a}.</p>

<p>Maaaring magdulot ito ng mga problemang pangmemorya sa Moodle sa mga susunod na panahon, lalo na
   kung marami kang binuhay na modyul at/o marami kang tagagamit.</p>

<p>Iminumungkahi namin na isaayos mo ang PHP na may mas mataas na limit kung maaari, tulad ng 40M.
    May iba\'t-ibang paraan na magagawa kayo upang ito ay maiisakatuparan:</p>
<ol>
<li>Kunga maaari mong gawin, muling ikompayl ang PHP na may <i>--enable-memory-limit</i>.  
     Pahihintulutan nito ang Moodle na itakda ang memory limit sa sarili nito.</li>
<li>Kung mapapasok mo ang iyong sakong php.ini, mababago mo ang <b>memory_limit</b> 
    na kaayusan doon at gawin itong mga 40M.  Kung wala kang karapatang pasukin ito
    baka puwede mong hilingin sa administrador na gawin ito para sa iyo.</li>
<li>Sa ilang PHP serve maaari kang lumikha ng isang sakong .htaccess sa bugsok ng Moodle
    na naglalaman ng linyang ito:
    <p><blockquote>php_value memory_limit 40M</blockquote></p>
    <p>Subali\'t sa ilang server ay pipigilin nito ang paggana ng <b>lahat</b> ng pahinang PHP 
    (makakakita ka ng mga error kapag tumingin ka sa mga pahina) kaya\'t kakailanganin mong tanggalin ang sakong .htaccess.</p></li>
</ol>';
$string['phpversion'] = 'Bersiyon ng PHP';
$string['phpversionhelp'] = '<p>Kinakailangan ng Moodle ang isang bersiyon ng PHP na kahit man lamang 4.3.0. o 5.1.0 (ang 5.0.x ay maraming problema)</p>
<p>Sa kasalukuyan ay pinatatakbo mo ang bersiyong {$a}</p>
<p>Kailangan mong gawing bago ang PHP o lumipat sa isang host na may mas bagong bersiyon ng PHP!<br />(Sa kaso ng 5.0.x ay maaari mo ring ibaba ang bersiyon sa 4.4.x)
</p>';
$string['welcomep10'] = '{$a->installername} ({$a->installerversion})';
$string['welcomep20'] = 'Nakikita mo ang pahinang ito dahil matagumpay mong nailuklok at napagana ang paketeng <strong>{$a->packname} {$a->packversion}</strong> sa iyong kompyuter.  Maligayang bati!';
$string['welcomep30'] = 'Ang lathala ng <strong>{$a->installername}</strong> na ito ay naglalaman ng mga aplikasyon na lilikha ng kapaligiran na tatakbuhan ng  <strong>Moodle</strong>, ito ay ang mga sumusunod:';
$string['welcomep40'] = 'Nilalaman din ng paketeng ito ang  <strong>Moodle {$a->moodlerelease} ({$a->moodleversion})</strong>.';
$string['welcomep50'] = 'Ang paggamit ng lahat ng aplikasyon sa paketeng ito ay alinsunod sa kani-kaniyang lisensiya.  Ang kumpletong pakete na <strong>{$a->installername}</strong> ay  <a href="http://www.opensource.org/docs/definition_plain.html">open source</a> at ipinamamahagi alinsunod sa lisensiyang <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a>';
$string['welcomep60'] = 'Dadalhin kayo ng mga sumusunod na pahina sa mga madaling hakbang upang maisaayos at mapatakbo ang <strong>Moodle</strong> sa kompyuter ninyo.  Kung gusto ninyo ay panatilihin ang umiiral o kaya ay baguhin ito ayon sa inyong pangangailangan.';
$string['welcomep70'] = 'Iklik ang "Susunod" na buton sa ibaba upang maituloy ang pasasaayos ng <strong>Moodle</strong>.';
