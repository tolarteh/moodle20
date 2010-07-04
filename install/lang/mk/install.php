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

$string['admindirname'] = 'Директориум аdmin';
$string['chooselanguagehead'] = 'Изберете јазик';
$string['chooselanguagesub'] = 'Изберете јазик САМО за инсталацијата. Подоцна ќе можете да изберете јазик за страницата и за корисниците.';
$string['dataroot'] = 'Директориум';
$string['dbprefix'] = 'Префикс на табели';
$string['dirroot'] = 'Moodle директориум';
$string['installation'] = 'Инсталација';
$string['langdownloaderror'] = 'За жал, јазикот "{$a}" не беше инсталиран. Инсталацискиот процес ќе продолжи на англиски.';
$string['memorylimithelp'] = '<p>Прагот на меморијата кај PHP за Вашиот компјутер моментално е подесена на {$a}. </p>

<p>Ова може да предизвика проблеми со меморијата подоцна, 
  посебно ако имате голем број на овозможени модули (единици) и/или голем број на корисници.</p>

<p>Ви препорачуваме да го конфигурирате PHP со најголем можен праг на меморија, како што е 16М.
   Еве неколку начини за да го постигнете тоа : </p>
<ol>
<li>Ако сте способен, повторно компајлирајте PHP со<i>--enable-memory-limit</i>.  
    Ова ќе овозможи на Moodle сам да го постави прагот на меморијата. </li>
<li> Ако имате пристап до Вашата датотека php.ini, можете да го промените <b>memory_limit</b> 
    на 16М. Во спротивно, ако немате пристап, можеби ќе бидете способни 
    да го замолите администраторот да ја заврши оваа работа.  </li>
<li>На некои PHP сервери, можете да креирате датотека .htaccess во директориумот на Moodle,
    која ги содржи следниве линии :
    <p><blockquote>php_value memory_limit 40M</blockquote></p>
    <p>Како и да е, на некои компјутери, ова може да ги спречи <b>сите </b> PHP страници да работат (ќе забележите грешки на страниците) па ќе треба да ја преместите датотеката .htaccess.</p></li>
</ol>';
$string['phpversion'] = 'Верзија на PHP';
$string['phpversionhelp'] = '<p>На Moodle му е потребна верзија на PHP, и тоа најмалку 4.1.0. </p>
<p>Моментално работите на верзијата {$a} </p>
<p>Мора да го обновите PHP, или да го преместите кај хост со понова верзија од PHP! </p>';
$string['welcomep10'] = '{$a->installername} ({$a->installerversion})';
$string['welcomep20'] = 'Ја гледате оваа страница затоа што успешно инсталиравте и вклучивте <strong>{$a->packname} {$a->packversion}</strong> пакет на Вашиот компјутер. Честитки!';
$string['welcomep30'] = 'Оваа верзија на <strong>{$a->installername}</strong> вклучува апликации за креирање околина во која <strong>Moodle</strong> ќе работи:';
$string['welcomep40'] = 'Овој пакет исто така вклучува <strong>Moodle {$a->moodlerelease} ({$a->moodleversion})</strong>.';
$string['welcomep50'] = 'Користењето на сите апликации во овој пакет се регулира со нејзините лиценци. Комплетниот <strong>{$a->installername}</strong> пакет е   <a href="http://www.opensource.org/docs/definition_plain.html">слободен софтвер</a> и е дистрибуиран под <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a> лиценцата.';
$string['welcomep60'] = 'Следните страници ќе Ве водат низ неколку лесни чекори за поставување на <strong>Moodle</strong> на Вашиот компјутер. Може да ги прифатите стандардните поставувања, или да ги промените за Ваши потреби.';
$string['welcomep70'] = 'Кликнете на копчето „Следно“ за да продолжите со поставување на <strong>Moodle</strong>.';
$string['wwwroot'] = 'Веб адреса';
