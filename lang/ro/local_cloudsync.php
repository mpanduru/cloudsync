<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_cloudsync
 * @category    string
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'cloudsync';
$string['manage'] = 'CloudSync';
$string['websitetag'] = 'Eticheta site-ului';
$string['websitetag_desc'] = 'Această etichetă va fi folosită pentru crearea resurselor din cloud. Numele ar trebui să conțină numai litere 
și / sau numere și nu caractere speciale. O resursă creată de modul în cloud va fi numită astfel: cloudsync_TipResursă_Etichetă';
$string['cloudsync:managecloud'] = 'Creare de mașini virtuale în cloud';
$string['dropdown_button'] = 'Cloud';
$string['guestcannotaccessresource'] = 'Oaspeții nu pot accesa resursele cloud. Vă rugăm să vă conectați cu un cont existent pentru a continua.';
$string['virtualmachinestitle'] = 'Mașini virtuale';
$string['cloudadministrationtitle'] = 'Administrare Cloud';
$string['mycloudheading'] = 'Mașinile mele virtuale';
$string['cloudadminrequestsheading'] = 'Cereri active';
$string['cloudadminactivesubscriptions'] = 'Abonamente active';
$string['cloudadminrequesttitle'] = 'Administrare cereri';
$string['newsubscriptiontitle'] = 'Adăugare abonament';
$string['virtualmachinetitle'] = 'Mașină virtuală';
$string['virtualmachinedetailstitle'] = 'Detalii mașină virtuală';
$string['newsubscription'] = 'Abonament nou';
$string['seeallrequests'] = 'Vezi toate cererile';
$string['delete'] = 'Șterge';
$string['type'] = 'Tip';
$string['deletesubscription'] = 'Șterge abonament';
$string['viewsubscription'] = 'Vezi VM-uri';
$string['request'] = 'Cere o mașină virtuală';
$string['reject'] = 'Respinge cerere';
$string['cloudrequest'] = 'Cere Mașină Virtuală';
$string['vmrequest_general'] = 'General';
$string['vmrequest_missing_value'] = 'Acest câmp este obligatoriu!';
$string['vmrequest_vmname'] = 'Nume mașină virtuală';
$string['vmrequest_vmname_help'] = 'Numele mașinii virtuale solicitate';
$string['vmrequest_teacher'] = 'Profesor';
$string['vmrequest_teacher_help'] = 'Numele profesorului care te-a ghidat spre solicitarea unei mașini virtuale';
$string['vmrequest_description'] = 'Pentru ce ai nevoie de o mașină virtuală?';
$string['vmrequest_description_help'] = 'Scurtă explicație a motivelor pentru care ai nevoie de un VM. Ar trebui să includă workflow-ul pe care dorești să îl realizezi.';
$string['vmrequest_specifications'] = 'Specificații';
$string['vmrequest_specifications_help'] = 'Specificațiile dorite pentru mașina virtuală. NOTĂ: Aceste specificații nu sunt cele finale și pot fi schimbate de către profesor!';
$string['vmrequest_os'] = 'Sistemul de operare al mașinii';
$string['vmrequest_memory'] = 'Memoria RAM a mașinii';
$string['vmrequest_processor'] = 'Numărul de nuclee ale procesorului';
$string['vmrequest_disk1_storage'] = 'Spațiul de stocare al discului primar';
$string['vmrequest_disk2_storage'] = 'Spațiul de stocare al discului secundar';
$string['vmrequest_not_primary_storage_help'] = 'Schimbă doar dacă vrei mai multe discuri';
$string['vmrequest_done'] = 'Trimite cerere';
$string['vmcreate_done'] = 'Creează VM';
$string['vmcreate_request'] = 'Cerere';
$string['vmcreate_user'] = 'Student';
$string['vmcreate_user_help'] = 'Utilizatorul care a solicitat mașina virtuală';
$string['vmcreate_teacher'] = 'Profesor';
$string['vmcreate_teacher_help'] = 'Profesorul coordonator';
$string['vmcreate_description'] = 'Descrierea cererii';
$string['vmcreate_description_help'] = 'Motivele pentru care studentul are nevoie de o mașină virtuală';
$string['vmcreate_os'] = 'Sistem de operare';
$string['vmcreate_memory'] = 'Memorie';
$string['vmcreate_processor'] = 'Număr de nuclee';
$string['vmcreate_disk1_storage'] = 'Spațiul de stocare al discului primar';
$string['vmcreate_disk2_storage'] = 'Spațiul de stocare al discului secundar';
$string['vmcreate_os_help'] = 'Sistemul de operare solicitat de student';
$string['vmcreate_memory_help'] = 'Memoria RAM solicitată de student';
$string['vmcreate_processor_help'] = 'Numărul de nuclee ale procesorului solicitat de student';
$string['vmcreate_disk1_storage_help'] = 'Spațiul de stocare al discului primar solicitat de student';
$string['vmcreate_disk2_storage_help'] = 'Spațiul de stocare al discului secundar solicitat de student';
$string['vmcreate_virtualmachine'] = 'Mașină Virtuală';
$string['vmcreate_subscription'] = 'Abonament Cloud';
$string['vmcreate_region'] = 'Regiune';
$string['vmcreate_type'] = 'Tip';
$string['vmcreate_disk1'] = 'Spațiu de stocare disc principal (Minimum 8GB)';
$string['vmcreate_disk2'] = 'Spațiu de stocare disc secundar';
$string['vmcreate_reject'] = 'Respinge cererea';
$string['vmcreate_info'] = 'Informații';
$string['vmcreate_message'] = 'Mesaj';
$string['vmcreate_message_help'] = 'Spune utilizatorului motivul respingerii cererii';
$string['subscriptionform_subscriptionname'] = 'Numele abonamentului';
$string['subscriptionform_subscriptionname_help'] = 'Acest numa va fi văzut de alți utilizatori la selectarea acestui abonament';
$string['subscriptionform_done'] = 'Salvare';
$string['deletevmtitle'] = 'Șterge Mașină Virtuală';
$string['deletesubscriptiontitle'] = 'Șterge Abonament';
$string['terminatevmtitle'] = 'Renunță la o mașină virtuală';
$string['deletevmquestion'] = 'Sunteți sigur că doriți să ștergeți mașina virtuală ';
$string['deletesubscriptionquestion'] = 'Sunteți sigur că doriți să ștergeți abonamentul ';
$string['terminatevmquestion'] = 'Sunteți sigur că doriți să renunțați la mașina ';
$string['terminatevmwarning'] = 'Dacă continuați nu veți mai putea accesa această mașină niciodată.';
$string['firstaccessattention'] = 'ATENȚIE! NU REÎNCĂRCAȚI PAGINA!';
$string['firstaccessmessage'] = 'Aceasta este prima ta accesare a acestei mașini virtuale. Apasă butonul de mai jos pentru a obține cheia ta privată SSH pentru conectarea la mașina ta virtuală. Salvează cheia pe dispozitivul tău, apoi reîmprospătează pagina pentru a vedea detaliile de conectare. Amintește-ți, odată ce reîmprospătezi pagina, cheia SSH nu va mai fi vizibilă, așa că asigură-te că urmezi acești pași cu atenție.';
$string['vmaccesscardtitle'] = 'Instrucțiuni de Conectare SSH';
$string['vmaccessexplanation'] = 'Trebuie să folosești un client SSH pentru a te conecta la această mașină virtuală. Urmează acești pași:';
$string['vmaccessstep1'] = 'Deschide clientul tău SSH preferat.';
$string['vmaccessstep2'] = 'Găsește fișierul cu cheia ta privată. Ar trebui să fi salvat această cheie când ai accesat prima dată această mașină virtuală.';
$string['vmaccessstep3'] = 'Dacă este necesar, execută această comandă (vei primi o eroare dacă cheia ta este vizibilă public):';
$string['vmaccessstep3command'] = 'chmod 400 ';
$string['vmaccessstep4'] = 'Conectează-te la instanța ta folosind această comandă (folosește powershell pentru Windows):';
$string['subscriptionspagetitle'] = 'Abonamente';
$string['singlesubscriptionpagetitle'] = 'Abonament';
$string['cloudprovidersubscription'] = 'Furnizor Cloud: ';
$string['subscriptionvmsheader'] = 'Abonament: ';
$string['subscriptionvmsvmname'] = 'Numele Mașinii Virtuale: ';
$string['subscriptionvmsvmuser'] = 'Utilizatorul Mașinii Virtuale: ';
$string['subscriptionvmscreatedat'] = 'Creat la: ';
$string['subscriptionvmscloudid'] = 'ID (Cloud): ';
$string['subscriptionvmsvmregion'] = 'Regiunea VM: ';
$string['subscriptionvmsvmtype'] = 'Tipul VM: ';
$string['subscriptionvmsvmstatus'] = 'Starea VM: ';
$string['subscriptionvmslastused'] = 'Ultima utilizare (web): ';
$string['subscriptionvmsgoto'] = 'Accesează';
$string['showkeybutton'] = 'Arată Cheia';
$string['activecloudsubscription'] = 'Abonamente de cloud active';
$string['selectcloudprovider'] = 'Selectează furnizor';
$string['adminvmlistviewdetails'] = 'Apasă pentru a vedea detaliile';
$string['adminvmlistnotaccessed'] = 'Utilizatorul nu a accesat încă această mașină virtuală.';
$string['uservmlistviewdetails'] = 'Apasă pentru a vedea detaliile de conectare';
$string['uservmlistnotaccessed'] = 'Nu ai accesat încă această mașină virtuală.';
$string['vmdetailstitle'] = 'Detalii Mașină Virtuală';
$string['vmdetailsowner'] = 'Proprietar: ';
$string['vmdetailscloudadmin'] = 'Administrator Cloud: ';
$string['vmdetailsvmname'] = 'Numele VM: ';
$string['vmdetailsdeletedat'] = 'Șters la: ';
$string['vmdetailsvmnotdeleted'] = 'VM-ul nu a fost încă șters';
$string['vmdetailsvmos'] = 'Sistem de operare VM: ';
$string['vmdetailstype'] = 'Tip specificații VM: ';
$string['vmdetailsstorage'] = 'Stocare VM: ';
$string['vmdetailssshkey'] = 'Numele cheii SSH: ';
$string['vmdetailsrequestinfo'] = 'Informații cerere mașină virtuală';
$string['vmdetailsrequestedat'] = 'Cerut la: ';
$string['vmdetailsapprovedat'] = 'Aprobat la: ';
$string['vmdetailsteacher'] = 'Coordonator profesor: ';
$string['vmdetailsdescription'] = 'Descriere: ';
$string['vmdetailsreqos'] = 'Sistem de operare VM solicitat: ';
$string['vmdetailsreqmem'] = 'Memorie VM solicitată: ';
$string['vmdetailsreqvcpu'] = 'VCPUs VM solicitate: ';
$string['vmdetailsreqdisk1'] = 'Stocare discul 1 solicitat: ';
$string['vmdetailsreqdisk2'] = 'Stocare discul 2 solicitat: ';
$string['vmdetailsreqno2nddisk'] = 'Utilizatorul nu a solicitat un disc secundar';
$string['vmdetailsnetinfo'] = 'Informații rețea';
$string['vmdetailsprivateip'] = 'Adresă IP privată: ';
$string['vmdetailspublicip'] = 'Adresă IP publică: ';
$string['vmdetailspublicdns'] = 'Nume DNS public: ';
$string['vmawaitsdelete'] = 'Această mașină așteaptă să fie ștearsă!';
$string['uservmrequeststitle'] = 'Istoric cereri VM';
$string['usersinglevmrequesttitle'] = 'Cerere VM';
$string['userreqlistheader'] = 'Istoric cereri mașină virtuală';
$string['userreqlistvm'] = 'VM solicitat';
$string['userreqliststatus'] = 'Stare cerere';
$string['userreqlistreqdate'] = 'Data cererii';
$string['userreqlistrespdate'] = 'Data răspunsului';
$string['userreqlistnotclosed'] = 'Răspuns în așteptare';
$string['userreqdetailsheader'] = 'Detalii cerere';
$string['userreqdetailsreqby'] = 'Solicitat de: ';
$string['userreqdetailsreqat'] = 'Solicitat la: ';
$string['userreqdetailsteacher'] = 'Coordonator profesor: ';
$string['userreqdetailsdescription'] = 'Descriere cerere: ';
$string['userreqdetailsreqname'] = 'Numele VM solicitat: ';
$string['userreqdetailsreqos'] = 'Sistem de operare VM solicitat: ';
$string['userreqdetailsreqmem'] = 'Memorie VM solicitată: ';
$string['userreqdetailsreqcpu'] = 'VCPUs VM solicitate: ';
$string['userreqdetailsreqstorage'] = 'Stocare VM solicitată: ';
$string['userreqdetailsresponsedetails'] = 'Detalii răspuns';
$string['userreqdetailsstatus'] = 'Stare: ';
$string['userreqdetailspending'] = 'Această cerere este în așteptarea unui răspuns.';
$string['userreqdetailsappat'] = 'Aprobat la: ';
$string['userreqdetailsappmessage'] = 'Mesaj de aprobare: ';
$string['userreqdetailsappby'] = 'Aprobat de: ';
$string['userreqdetailsrejat'] = 'Respins la: ';
$string['userreqdetailsrejmessage'] = 'Mesaj de respingere: ';
$string['userreqdetailsrejby'] = 'Respins de: ';
$string['userreqdetailsvmdetails'] = 'Detalii VM';
$string['userreqdetailsvmname'] = 'Nume: ';
$string['userreqdetailsvmstatus'] = 'Stare VM: ';
$string['userreqdetailsvmkey'] = 'Cheie: ';
$string['userreqdetailsvmos'] = 'Sistem de operare alocat: ';
$string['userreqdetailsvmmemory'] = 'Memorie alocată: ';
$string['userreqdetailsvmcpus'] = 'VCPUs alocate: ';
$string['userreqdetailsvmstorage'] = 'Stocare alocată: ';
$string['adminvmrequestsactivetitle'] = ' (Active)';
$string['adminvmrequestsclosedtitle'] = ' (Închise)';
$string['adminreqlistheader'] = 'Cererile mașinilor virtuale';
$string['adminreqlistuser'] = 'Utilizator';
$string['adminreqlistteacher'] = 'Profesor';
$string['adminreqlistmanage'] = 'Gestionează cererea';
$string['adminreqlistinfo'] = 'Detalii cerere';
$string['activerequests'] = 'Cereri mașini virtuale';
$string['adminreqlistseeclosed'] = 'Sari la cererile închise';
$string['adminreqlistseeactive'] = 'Sari la cererile active';
$string['reqdetailscloudinfo'] = 'Informații Cloud VM';
$string['rejectrequesttitle'] = 'Respinge Cererea';
$string['rejectrequestquestion1'] = 'Ești sigur că vrei să respingi cererea lui ';
$string['rejectrequestquestion2'] = '?';
$string['vmnotrunninguser'] = 'VM-ul tău nu rulează. Te rugăm să contactezi administratorul pentru mai multe informații dacă problema persistă.';
$string['adminvmlistdeleted'] = ' (Șters)';
$string['adminvmlistseedeleted'] = 'Sari la VM-urile șterse';
$string['adminvmlistseeactive'] = 'Sari la VM-urile active';
$string['usersshkeystitle'] = 'Cheile mele SSH';
$string['usersshkeysname'] = 'Nume cheie';
$string['usersshkeysvms'] = 'Mașini virtuale asociate';
$string['userreqdetailsdeletedat'] = 'Șters la: ';
$string['userreqdetailsdeletedby'] = 'Șters de: ';
$string['changesshpermissions'] = 'Schimbă permisiunile cheii';
$string['changesshpermissions2'] = '(Dacă nu ai făcut-o când ai salvat prima dată cheia):';
$string['changesshpermissionslinux'] = 'Linux sau MacOS:';
$string['changesshpermissionswindows'] = 'Windows:';
$string['changesshpermissionswindows1'] = 'Clic dreapta pe ';
$string['changesshpermissionswindows2'] = 'Selectează Proprietăți';
$string['changesshpermissionswindows3'] = 'Mergi la fila Securitate -> Avansat';
$string['changesshpermissionswindows4'] = 'Dezactivează Moștenirea';
$string['changesshpermissionswindows5'] = 'Selectează "Convertiți permisiunile moștenite în permisiuni explicite pentru acest obiect"';
$string['changesshpermissionswindows6'] = 'Clic pe Utilizatori în Intrările de permisiuni';
$string['changesshpermissionswindows7'] = 'Apasă Eliminați';
$string['changesshpermissionswindows8'] = 'Clic Aplică -> OK -> OK';
$string['firstaccessinstructionstitle'] = 'Te rugăm să urmezi instrucțiunile de mai jos pentru a salva cheia ta';
$string['firstaccessinstr1'] = 'Apasă "Arată Cheia" pe butonul de mai jos';
$string['firstaccessinstr2'] = 'Copiază cheia care apare pe pagina ta';
$string['firstaccessinstr3'] = 'Lipește cheia într-un fișier numit ';
$string['firstaccessinstr4'] = 'Schimbă permisiunile cheii:';
$string['firstaccessinstr5'] = 'Gata! Acum poți reîmprospăta pagina pentru a obține detaliile de conectare.';
$string['vmreqcloudprovider'] ='Furnizor cloud: '; 
$string['vmreqsubscription'] ='Abonament: ';
$string['vmreqregion'] ='Regiune: ';
$string['vmreqidcloud'] ='ID (Cloud): ';
$string['vmreqtype'] ='Tip: ';
