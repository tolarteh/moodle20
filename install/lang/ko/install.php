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

$string['admindirname'] = '관리 디렉토리';
$string['availablelangs'] = '가능한 언어 목록';
$string['chooselanguagehead'] = '언어를 선택하시오';
$string['chooselanguagesub'] = '설치 과정에서 사용할 언어를 선택하십시오. 선택한 언어는 사이트의 기본 언어로 사용할 수 있으며, 추후 다른 언어로 바꿀 수도 있습니다.';
$string['cliinstallheader'] = '무들 {$a} 명령 입력 설치 프로그램';
$string['databasehost'] = '데이터베이스 호스트 :';
$string['databasename'] = '데이터베이스 명칭 :';
$string['databasetypehead'] = '데이터베이스 드라이버 선택';
$string['dataroot'] = '데이타 경로';
$string['dbprefix'] = '테이블 접두어';
$string['dirroot'] = '무들 디렉토리';
$string['environmentsub2'] = '개개의 무들 배포본은 필요로하는 최소한의 PHP 버전과 확장기능이 다릅니다. 각 판을 설치하거나 판올림하기 전에 완벽한 구동환경을 점검해야 합니다. 혹 여러분이 어떻게 새 판을 설치해야 할지 또 어떻게 PHP 확장 기능을 설치해야 할지 모르겠다면, 서버 관리자에게 문의하기 바랍니다.';
$string['errorsinenvironment'] = '환경설정에 오류가 있습니다!';
$string['installation'] = '설치';
$string['langdownloaderror'] = '안타깝게도 "{$a}" 언어팩이 설치되지 않았습니다. 대신 영어를 이용하여 설치될 것입니다.';
$string['memorylimithelp'] = '<p>현재 서버의 PHP 메모리 사용량은 {$a} 로 설정되어 있습니다.</p>

<p>이는 추후에 무들이 원활히 구동되는 데 문제가 될 것입니다. 특히 여러분이 상당히 많은 모듈을 이용하고 또 사용자가 많아지게 되면 문제가 될 소지가 더 커집니다.</p>

<p>PHP가 사용할 수 있는 메모리 용랑을 40M 나 아니면 더 큰 값으로 설정하길 권합니다. 설정하는 방법은 
여러가지가 있습니다.</p>
<ol>
<li>만약 PHP소스를 재컴파일 할 수 있다면 옵션에 <i>--enable-memory-limit</i> 을 포함시켜 컴파일 하십시오. 이렇게 해 놓으면 무들 프로그램으로 메모리 용량을 제어할 수 있게 됩니다.</li>

<li>만약 php.ini 파일에 접근 가능하다면 당신은 <b>memory_limit 40M</b> 처럼 값을 바꿀 수 있을것입니다. 만약 여러분이 직접 접근 할 수 없다면 서버 관리자에게 요청하여 처리하실 수 있습니다.</li>

<li>또 도저히 php.ini 안에 있는 값을 바꿀 수가 없다면 무들 디렉토리에 아래와 같은 내용을 포함하는 .htaccess 를 넣어두면 됩니다.
<P><blockquote>php_value memory_limit 40M<blockquote></p>
<p>그러나 어떤 서버에서는 이러한 기능이 모든 PHP페이지에 적용되어 버릴 수도 있게 되는 데 (당신이 페이지를 살펴보았을때 문제를 찾을 것이다) 이 때에는 .htaccess 를 제거해야 하고 다른 방안을 찾아봐야 할 것입니다.</p></li></ol>';
$string['paths'] = '경로';
$string['pathserrcreatedataroot'] = '설치 스크립트가 자료 디렉토리 ({$a->dataroot}) 를 생성할 수 없습니다.';
$string['pathshead'] = '경로 확인';
$string['pathsrodataroot'] = 'Dataroot 디렉토리의 쓰기허가권이 없습니다.';
$string['pathsroparentdataroot'] = '상위 경로 ({$a->parent}) 에 쓰기허가권이 없습니다. 설치 스크립트가 자료 디렉토리 ({$a->dataroot}) 를 생성할 수 없습니다.';
$string['pathssubadmindir'] = '간혹 어떤 웹호스트 업체는 제어판 등을 제공하는 특별한 URL으로서 /admin을 사용합니다. 불행하게도 이것은 무들 관리페이지를 위한 표준 위치와 충돌을 일으킵니다. 설치과정에서 관리 디렉토리의 이름을 바꿈으로서 이 문제를 고칠수 있는 데, 다음의 예와 같이 새이름을 여기에 넣으면 됩니다. 예: <em>moodleadmin</em> 이렇게 하면 무들에서 관리자 링크문제가 해결됩니다.';
$string['pathssubdataroot'] = '무들로 업로드된 파일을 저장할 수 있는 장소가 필요합니다. 이 디렉토리는 웹 서버의 사용자(보통 "none" 또는 "apache" )에 의해서 \'읽고쓰기 가능\' 권한을 보유하여야 합니다. 그러나 직접적으로 웹을 경유해서 접근할 수 있어서는 안됩니다.';
$string['pathssubdirroot'] = '무들 설치를 위한 완전한 디렉토리 경로. 심볼릭 링크를 사용하기 위해 꼭 필요한 경우 변경';
$string['pathssubwwwroot'] = '무들을 접속할 수 있는 전체 웹 주소. 다중 주소를 이용해서는 무들에 접속할 수 없음.
만일 사이트가 복수의 공개 주소를 갖고 있는 경우, 여기에 입력한 주소 이외의 곳에서는 영구적인 redirect를 설정해 놓아야만 함.
만약 여러분의 사이트를 인터넷과 인트라넷 모두에서 접속할 수 있게 하려면 여기에 공식적인 주소를 입력하고 DNS를 설정해서 인트라넷 사용자들도 공개 주소를 사용할 수 있게 해야할 것입니다.';
$string['pathsunsecuredataroot'] = 'Dataroot 경로가 안전하지 않음';
$string['pathswrongadmindir'] = '관리자 경로가 존재하지 않음';
$string['pathswrongdirroot'] = '잘못된 dirroot 위치';
$string['phpextension'] = '{$a} PHP 확장';
$string['phpversion'] = 'php버젼';
$string['phpversionhelp'] = '<p>무들은 적어도 PHP4.3.0 혹은 5.1.0. 이상 이어야합니다.(5.0.x는 버그가 있다고 알려져 있습니다)</p>
<p>현재 구동되고 있는 PHP버전은 {$a} 입니다.</p>
<p>PHP를 업그레이드 하시거나 새버전을 제공하는 웹호스팅 업체로 이전하기를 권합니다!<br />(만일 5.0.x버전을 사용 중이라면 4.4.x 버전으로 다운그레이드 할 수 있습니다)</p>';
$string['welcomep10'] = '{$a->installername} ({$a->installerversion})';
$string['welcomep20'] = '당신의 컴퓨터에 <strong>{$a->packname} {$a->packversion}</strong> 패키지를 성공적으로 설치한 것을 축하합니다!';
$string['welcomep30'] = '<strong>{$a->installername}</strong> 의 이 릴리스는 <strong>무들</strong>이 그 속에서 동작하는 환경을 생성하기 위한 어플리케이션을 포함하고 있습니다.';
$string['welcomep40'] = '이 패키지는 <strong>무들 {$a->moodlerelease} ({$a->moodleversion})</strong> 을 포함하고 있습니다.';
$string['welcomep50'] = '이 패키지에 있는 모든 어플리케이션을 사용하는 것은 각각의 라이센스에의해 지배받습니다. 완전한<strong>{$a->installername}</strong> 패키지는
<a href="http://www.opensource.org/docs/definition_plain.html">공개 소스이며 </a> <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a> 라이선스에 의해 배포됩니다.';
$string['welcomep60'] = '다음 화면들은 당신의 컴퓨터에 <strong>무들</strong>을 설정하고 설치하는 길라잡이 역할을 할 것입니다. 기본 설정을 선택하거나 목적에 맞게 선택적으로 수정할 수 있습니다.';
$string['welcomep70'] = '<strong>무들</strong> 설정을 계속하기 위해서는 "다음" 버튼을 클릭하세요.';
$string['wwwroot'] = '웹 주소';
