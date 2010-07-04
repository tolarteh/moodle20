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

$string['admindirname'] = 'ថត​អ្នក​គ្រប់គ្រង';
$string['chooselanguagehead'] = 'ជ្រើស​ភាសា';
$string['chooselanguagesub'] = 'សូម​ជ្រើស​ភាសា សម្រាប់​តែ​ការ​ដំឡើង​ប៉ុណ្ណោះ ។ អ្នក​នឹង​អាច​ជ្រើស​ភាសា​សម្រាប់​តំបន់​បណ្ដាញ និង​អ្នក​ប្រើ នៅ​ផ្នែក​ក្រោយ​ៗ​ទៀត ។';
$string['dataroot'] = 'ថត​ទិន្នន័យ';
$string['dbprefix'] = 'បុព្វបទ​តារាង';
$string['dirroot'] = 'ថត Moodle';
$string['installation'] = 'ការ​ដំឡើង';
$string['langdownloaderror'] = 'ភាសា "{$a}" មិនត្រូវ​បាន​ដំឡើង​ឡើយ ។ វា​នឹង​បន្ត​ដំឡើង​ជា​ភាសា​អង់គ្លេស ។';
$string['memorylimithelp'] = '<p>បច្ចុប្បន្ន សតិ​កំណត់​របស់ PHP សម្រាប់​ម៉ាស៊ីន​បម្រើ​របស់​អ្នក ត្រូវ​បាន​កំណត់​ជា {$a} ។</p>

<p>វា​អាច​បង្ក​ឲ្យ Moodle មាន​បញ្ហា​ជាមួយ​សតិ​នៅ​ពេល​ក្រោយ ជា​ពិសេស​នៅពេល​​អ្នក​មាន​ម៉ូឌុល​​បើក​ច្រើន និង/ឬ​អ្នក​ប្រើ​ច្រើន ។</p>

<p>យើង​ផ្ដល់​អនុសាសន៍​ថា អ្នក​គួរតែ​កំណត់​រចនា​សម្ព័ន្ធ PHP ជាមួយ​ចំនួន​កំណត់​ខ្ពស់​ជាងនេះ​តាម​ដែល​អាច ដូច​ជា 40M ។
មាន​វិធី​ជា​ច្រើន ក្នុង​ការ​ធ្វើ​វា ដែល​អ្នក​អាច​សាកល្បង​បាន ៖</p>
<ol>
<li>ប្រសិនបើ​អ្នក​អាច​ចងក្រង PHP ឡើង​វិញ​បាន​ដោយ​ប្រើ <i>--enable-memory-limit</i> ។
នោះ Moodle នឹង​អាច​កំណត់​​សតិ​កំណត់​ដោយ​ខ្លួន​ឯង ។</li>
<li>ប្រសិនបើ​អ្នក​មាន​សិទ្ធិ​ចូល​ដំណើរ​ការ​ឯកសារ php.ini របស់​អ្នក អ្នក​អាច​ផ្លាស់ប្ដូរ​ការ​កំណត់ <b>memory_limit</b> ក្នុង​ទីនោះ​ទៅ​តម្លៃ​ផ្សេង ដូច​ជា 40M ។ ប្រសិនបើ​អ្នក​មិន​មាន​សិទ្ធិ​ចូល​ដំណើរ​ការ​ទេ​នោះ អ្នក​អាច​ស្នើ​ឲ្យ​អ្នក​គ្រប់គ្រង​របស់​អ្នក​ធ្វើ​​ឲ្យ​អ្នក​តែ​ម្ដង ។</li>
<li>នៅ​លើ​ម៉ាស៊ីន​បម្រើ PHP មួយចំនួន ​អ្នក​អាច​បង្កើត​ឯកសារ .htaccess នៅ​ក្នុង​ថត Moodle ដែល​មាន​បន្ទាត់​នេះ ៖
<p><blockquote>php_value memory_limit 40M</blockquote></p>
<p>ទោះយ៉ាង​ណា នៅ​លើ​ម៉ាស៊ីន​បម្រើ​មួយចំនួន ទង្វើរ​នេះ​នឹង​រារាំង​ទំព័រ PHP ​<b>ទាំងអស់</b> មិន​ឲ្យ​ដំណើរការ
(អ្នក​នឹង​ឃើញ​កំហុស នៅពេល​អ្នក​មើល​ទំព័រ) ដូច្នេះ​អ្នក​ត្រូវ​តែ​យក​ឯកសារ .htaccess ចេញ ។</p></li>
</ol>';
$string['phpversion'] = 'កំណែ PHP';
$string['phpversionhelp'] = '<p>Moodle ទាមទារ​កំណែ PHP យ៉ាង​ហោច​ណាស់​ត្រឹម 4.3.0 ឬ 5.1.0 (5.0.x នៅ​មាន​បញ្ហា​មួយ​ចំនួន) ។</p>
<p>អ្នក​កំពុង​រត់​កំណែ {$a}</p>
<p>អ្នក​ត្រូវ​តែ​ធ្វើ​ឲ្យ PHP ប្រសើរ ឬ​ផ្លាស់ទី​ទៅ​ម៉ាស៊ីន​ដែល​មាន​កំណែ PHP ថ្មី ‌!<br/>(ក្នុង​ករណី 5.0.x អ្នក​ក៏​អាច​បន្ទាប​ទៅ​កំណែ 4.4.x ផង​ដែរ)</p>';
$string['welcomep10'] = '{$a->installername} ({$a->installerversion})';
$string['welcomep20'] = 'អ្នក​កំពុង​មើល​ទំព័រ​នេះ ពីព្រោះ​អ្នក​បាន​ដំឡើង និង​ចាប់ផ្ដើម​ដំណើរការ​កញ្ចប់ <strong>{$a->packname} {$a->packversion}</strong> ដោយ​ជោគជ័យ ក្នុង​កុំព្យូទ័រ​របស់​អ្នក ។ សូម​អបអរ​សាទរ!';
$string['welcomep30'] = 'ការ​ចេញ​ផ្សាយ <strong>{$a->installername}</strong> នេះ រួម​បញ្ចូល​នូវ​កម្មវិធី​សម្រាប់​បង្កើត​បរិស្ថាន​មួយ​ដែល <strong>Moodle</strong> នឹង​ប្រតិបត្តិ ។ វា​មាន​ឈ្មោះ​ថា ៖';
$string['welcomep40'] = 'កញ្ចប់​ក៏​​រួម​បញ្ចូល​ផងដែរ​នូវ <strong>Moodle {$a->moodlerelease} ({$a->moodleversion})</strong> ។';
$string['welcomep50'] = 'ការ​ប្រើប្រាស់​កម្មវិធី​ទាំងអស់​ក្នុង​កញ្ចប់​នេះ ត្រូវ​បាន​គ្រប់គ្រង​ដោយ​​អាជ្ញាបណ្ណ​របស់​ពួក​វា​រៀងៗ​ខ្លួន ។ កញ្ចប់ <strong>{$a->installername}</strong> ពេញ​លេញ​គឺ​ជា <a href="http://www.opensource.org/docs/definition_plain.html">កម្មវិធី​កូដ​បើកចំហ</a> ហើយ​ត្រូវ​បាន​ចែកចាយ​ក្រោម​អាជ្ញាបណ្ណ <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a> ។';
$string['welcomep60'] = 'ទំព័រ​ខាង​ក្រោម នឹង​ណែនាំ​អ្នកតាម​ជំហាន​ដ៏​ងាយស្រួល​ ដើម្បីកំណត់​រចនា​សម្ព័ន្ធ និង​រៀបចំ <strong>Moodle</strong> នៅ​លើ​កុំព្យូទ័រ​របស់​អ្នក ។ អ្នក​អាច​ទទួលយក​នូវ​ការ​កំណត់​លំនាំ​ដើម ឬ​​កែ​ប្រែ​ពួក​វា​ឲ្យ​ត្រូវ​ទៅ​នឹង​តម្រូវ​ការ​របស់​អ្នក​ផ្ទាល់ ។';
$string['welcomep70'] = 'ចុច​ប៊ូតុង "បន្ទាប់" នៅ​ខាង​ក្រោម ដើម្បី​បន្ត​រៀបចំ <strong>Moodle</strong> ។';
$string['wwwroot'] = 'អាសយដ្ឋាន​បណ្ដាញ';
