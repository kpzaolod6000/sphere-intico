<?php

namespace Modules\Sales\Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $emailTemplate = [
            'Meeting assigned',
            'New Quotation',
            'New Sales Order',
            'New Sales Invoice',
            'Sales Invoice Sent',
        ];

        $defaultTemplate = [
            'Meeting assigned' => [
                'subject' => 'Meeting Assign',
                'variables' => '{
                    "Meeting Assign User": "meeting_assign_user",
                    "Meeting Name": "meeting_name",
                    "Meeting Start Date": "meeting_start_date",
                    "Meeting Due Date": "meeting_due_date",
                    "Description": "description",
                    "Attendeed User": "attendees_user",
                    "Attendees Contact": "attendees_contact",
                    "App Url": "app_url",
                    "App Name": "app_name"
                  }',
                'lang' => [
                    'ar' => '<p>عزيزي ، { attasing_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>تم تخصيصك لاجتماع جديد :</p>
                            <p>الاسم : { attabing_name }</p>
                            <p>تاريخ البدء : { attabing_start_date }</p>
                            <p>تاريخ الاستحقاق : { batuinging_duse_date }</p>
                            <p>&nbsp;</p>
                            <p>Regards نوع ،</p>
                            <p>{ app_name }</p>',
                    'da' => '<p>K&aelig;re, { meeting_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Du er blevet tildelt til et nyt m&oslash;de:</p>
                            <p>Navn: { meeting_name }</p>
                            <p>Startdato: { meeting_start_date }</p>
                            <p>Forfaldsdato: { meeting_due_date }</p>
                            <p>&nbsp;</p>
                            <p>Kind Hilds,</p>
                            <p>{ app_name }</p>',
                    'de' => '<p>Sehr geehrte Frau, {meeting_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Sie wurden einer neuen Besprechung zugeordnet:</p>
                            <p>Name: {meeting_name}</p>
                            <p>Start Date: {meeting_start_date}</p>
                            <p>F&auml;lligkeitsdatum: {meeting_due_date}</p>
                            <p>&nbsp;</p>
                            <p>G&uuml;tige Gr&uuml;&szlig;e,</p>
                            <p>{Anwendungsname}</p>',
                    'en' => '<p>Dear, {meeting_assign_user}</p>
                            <p>You have been assigned to a new meeting:</p>
                            <p><strong>Name</strong>: {meeting_name}<br /><strong>Start Date</strong>: {meeting_start_date}<br /><strong>Due date</strong>: {meeting_due_date}<br /><br /><br />Kind Regards,<br />{app_name}</p>',
                    'es' => '<p>Estimado, {meeting_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Se le ha asignado una nueva reuni&oacute;n:</p>
                            <p>Nombre: {meeting_name}</p>
                            <p>Fecha de inicio: {meeting_start_date}</p>
                            <p>Fecha de vencimiento: {meeting_due_date}</p>
                            <p>&nbsp;</p>
                            <p>Bondadoso,</p>
                            <p>{app_name}</p>',
                    'fr' => '<p>Cher, { meeting_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Vous avez &eacute;t&eacute; affect&eacute; &agrave; une nouvelle r&eacute;union:</p>
                            <p>Nom: { meeting_name }</p>
                            <p>Date de d&eacute;but: { meeting_start_date }</p>
                            <p>Date d &eacute;ch&eacute;ance: { meeting_due_date }</p>
                            <p>&nbsp;</p>
                            <p>Kind Regards,</p>
                            <p>{ nom_app }</p>',
                    'it' => '<p>Caro, {meeting_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Ti &egrave; stato assegnato un nuovo incontro:</p>
                            <p>Nome: {meeting_name}</p>
                            <p>Data di inizio: {meeting_start_date}</p>
                            <p>Data di scadenza: {meeting_due_date}</p>
                            <p>&nbsp;</p>
                            <p>Kind Regards,</p>
                            <p>{app_name}</p>',
                    'ja' => '<p>デッドロック、 {meeting_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>新規ミーティングに割り当てられました :</p>
                            <p>名前: {meeting_name}</p>
                            <p>開始日: {meeting_start_date}</p>
                            <p>予定日: {meeting_due_date}</p>
                            <p>&nbsp;</p>
                            <p>カンド・リーカード</p>
                            <p>{app_name}</p>',
                    'nl' => '<p>Dear, { meeting_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>U bent toegewezen aan een nieuwe vergadering:</p>
                            <p>Naam: { meeting_name }</p>
                            <p>Begindatum: { meeting_start_date }</p>
                            <p>Vervaldatum: { meeting_due_date }</p>
                            <p>&nbsp;</p>
                            <p>Vriendelijke groeten,</p>
                            <p>{ app_name }</p>',
                    'pl' => '<p>Szanowny, {meeting_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Użytkownik został przypisany do nowego spotkania:</p>
                            <p>Nazwa: {meeting_name }</p>
                            <p>Data rozpoczęcia: {meeting_start_date }</p>
                            <p>Termin realizacji: {meeting_due_date }</p>
                            <p>&nbsp;</p>
                            <p>W Odniesieniu Do Rodzaju,</p>
                            <p>{app_name }</p>',
                    'ru' => '<p>Уважаемый, { meeting_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Вам назначено новое собрание:</p>
                            <p>Имя: { meeting_name }</p>
                            <p>Начальная дата: { meeting_start_date }</p>
                            <p>Дата выполнения: { meeting_due_date }</p>
                            <p>&nbsp;</p>
                            <p>Привет.</p>
                            <p>{ имя_программы }</p>',
                    'pt' => '<p>Querido, {meeting_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Voc&ecirc; foi designado para uma nova reuni&atilde;o:</p>
                            <p>Nome: {meeting_name}</p>
                            <p>Data de in&iacute;cio: {meeting_start_date}</p>
                            <p>Prazo de vencimento: {meeting_due_date}</p>
                            <p>&nbsp;</p>
                            <p>Esp&eacute;cie Considera,</p>
                            <p>{app_name}</p>',
                ],
            ],
            'New Quotation' => [
                'subject' => 'Quotation Create',
                'variables' => '{
                    "Quote Number": "quote_number",
                    "Billing Address": "billing_address",
                    "Shipping Address": "shipping_address",
                    "Description": "description",
                    "Date Quoted": "date_quoted",
                    "Quote Assign User": "quote_assign_user",
                    "App Url": "app_url",
                    "App Name": "app_name"
                  }',
                'lang' => [
                    'ar' => '<p>عزيزي ، { quote_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>لقد تم تخصيصك لاقتباس جديد : رقم التسعير : { quote_number }</p>
                            <p>عنوان الفواتير : { billing_address }</p>
                            <p>عنوان الشحن : { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Regards نوع ،</p>
                            <p>{ app_name }</p>',
                    'da' => '<p>K&aelig;re, { quote_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Du er blevet tildelt et nyt tilbud:</p>
                            <p>Tilbudsnummer: { quote_number }</p>
                            <p>Faktureringsadresse: { billing_address }</p>
                            <p>Shipping Address: { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Kind Hilds,</p>
                            <p>{ app_name }</p>',
                    'de' => '<p>Sehr geehrte Frau, {quote_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Sie wurden einem neuen Angebot zugeordnet:</p>
                            <p>Angebotsnummer: {quote_number}</p>
                            <p>Rechnungsadresse: {billing_address}</p>
                            <p>Versandadresse: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>G&uuml;tige Gr&uuml;&szlig;e,</p>
                            <p>{Anwendungsname}</p>',
                    'en' => '<p>Dear, {quote_assign_user}</p>
                            <p>You have been assigned to a new quotation:</p>
                            <p><strong>Quote Number</strong> : {quote_number}</p>
                            <p><strong>Billing Address</strong> : {billing_address}</p>
                            <p><strong>Shipping Address</strong> :&nbsp; {shipping_address}</p>
                            <p><br />Kind Regards,<br />{app_name}</p>',
                    'es' => '<p>Estimado, {quote_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Se le ha asignado un nuevo presupuesto:</p>
                            <p>N&uacute;mero de presupuesto: {quote_number}</p>
                            <p>Direcci&oacute;n de facturaci&oacute;n: {billing_address}</p>
                            <p>Direcci&oacute;n de env&iacute;o: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>Bondadoso,</p>
                            <p>{app_name}</p>',
                    'fr' => '<p>Cher, { quote_utilisateur_quo; }</p>
                            <p>&nbsp;</p>
                            <p>Vous avez &eacute;t&eacute; affect&eacute; &agrave; un nouveau devis:</p>
                            <p>Num&eacute;ro de devis: { quote_number }</p>
                            <p>Adresse de facturation: { adresse_facturation }</p>
                            <p>Adresse d exp&eacute;dition: { adresse_livraison }</p>
                            <p>&nbsp;</p>
                            <p>Kind Regards,</p>
                            <p>{ nom_app }</p>',
                    'it' => '<p>Caro, {quote_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Sei stato assegnato a una nuova quotazione:</p>
                            <p>Quote Numero: {quote_numero}</p>
                            <p>Indirizzo fatturazione: {billing_address}</p>
                            <p>Shipping Address: {indirizzo_indirizzo}</p>
                            <p>&nbsp;</p>
                            <p>Kind Regards,</p>
                            <p>{app_name}</p>',
                    'ja' => '<p>ディア、 {quote_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>新規見積に割り当てられています。</p>
                            <p>見積番号 : {quote_number}</p>
                            <p>請求先住所 : {billing_address}</p>
                            <p>出荷先住所 : {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>カンド・リーカード</p>
                            <p>{app_name}</p>',
                    'nl' => '<p>Geachte, { quote_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>U bent toegewezen aan een nieuwe prijsopgave:</p>
                            <p>Quote-nummer: { quote_number }</p>
                            <p>Factureringsadres: { billing_address }</p>
                            <p>Verzendadres: { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Vriendelijke groeten,</p>
                            <p>{ app_name }</p>',
                    'pl' => '<p>Szanowny, {quote_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Użytkownik został przypisany do nowego notowania:</p>
                            <p>Numer oferty: {numer_cytowania }</p>
                            <p>Adres do faktury: {adres_faktury }</p>
                            <p>Adres dostawy: {adres_shipp_}</p>
                            <p>&nbsp;</p>
                            <p>W Odniesieniu Do Rodzaju,</p>
                            <p>{app_name }</p>',
                    'ru' => '<p>Уважаемый, { quote_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Вам назначено новое предложение:</p>
                            <p>Номер предложения: { quote_number }</p>
                            <p>Адрес выставления счета: { billing_address }</p>
                            <p>Адрес доставки: { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Привет.</p>
                            <p>{ имя_программы }</p>',
                    'pt' => '<p>Querido, {quote_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Voc&ecirc; foi designado para uma nova cita&ccedil;&atilde;o:</p>
                            <p>Quote N&uacute;mero: {quote_number}</p>
                            <p>Endere&ccedil;o de cobran&ccedil;a: {billing_address}</p>
                            <p>Endere&ccedil;o de envio: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>Esp&eacute;cie Considera,</p>
                            <p>{app_name}</p>',
                ],
            ],
            'New Sales Order' => [
                'subject' => 'Sales Order Create',
                'variables' => '{
                    "Quote Number": "quote_number",
                    "Billing Address": "billing_address",
                    "Shipping Address": "shipping_address",
                    "Description": "description",
                    "Date Quoted": "date_quoted",
                    "Sales Order Assign User": "salesorder_assign_user",
                    "App Url": "app_url",
                    "App Name": "app_name"
                  }',
                'lang' => [
                    'ar' => '<p>عزيزي ، { مبيعات البيع _ assign_user }</p>
                            <p>&nbsp;</p>
                            <p>تم تخصيصك لعرض أسعار جديد :</p>
                            <p>رقم التسعير : { quote_number }</p>
                            <p>عنوان الفواتير : { billing_address }</p>
                            <p>عنوان الشحن : { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Regards نوع ،</p>
                            <p>{ app_name }</p>',
                    'da' => '<p>K&aelig;re, { salesorder_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Du har f&aring;et tildelt et nyt tilbud:</p>
                            <p>Tilbudsnummer: { quote_number }</p>
                            <p>Faktureringsadresse: { billing_address }</p>
                            <p>Shipping Address: { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Kind Hilds,</p>
                            <p>{ app_name }</p>',
                    'de' => '<p>Sehr geehrte Frau, {salesorder_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Sie wurden einem neuen Angebot zugeordnet:</p>
                            <p>Angebotsnummer: {quote_number}</p>
                            <p>Rechnungsadresse: {billing_address}</p>
                            <p>Versandadresse: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>G&uuml;tige Gr&uuml;&szlig;e,</p>
                            <p>{Anwendungsname}</p>',
                    'en' => '<p>Dear, {salesorder_assign_user}</p>
                            <p>You have been assigned to a new quotation:</p>
                            <p><strong>Quote Number</strong>&nbsp;: {quote_number}</p>
                            <p><strong>Billing Address</strong>&nbsp;: {billing_address}</p>
                            <p><strong>Shipping Address</strong>&nbsp;:&nbsp; {shipping_address}</p>
                            <p><br />Kind Regards,<br />{app_name}</p>',
                    'es' => '<p>Estimado, {salesorder_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Se le ha asignado una nueva cotizaci&oacute;n:</p>
                            <p>N&uacute;mero de presupuesto: {quote_number}</p>
                            <p>Direcci&oacute;n de facturaci&oacute;n: {billing_address}</p>
                            <p>Direcci&oacute;n de env&iacute;o: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>Bondadoso,</p>
                            <p>{app_name}</p>',
                    'fr' => '<p>Cher, { utilisateur_assignateur_vendeur }</p>
                            <p>&nbsp;</p>
                            <p>Vous avez &eacute;t&eacute; affect&eacute; &agrave; une nouvelle offre:</p>
                            <p>Num&eacute;ro de devis: { quote_number }</p>
                            <p>Adresse de facturation: { adresse_facturation }</p>
                            <p>Adresse d exp&eacute;dition: { adresse_livraison }</p>
                            <p>&nbsp;</p>
                            <p>Kind Regards,</p>
                            <p>{ nom_app }</p>',
                    'it' => '<p>Caro, {salesorder_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Ti &egrave; stato assegnato una nuova quotazione:</p>
                            <p>Numero preventivo: {quote_number}</p>
                            <p>Billing Address: {billing_address}</p>
                            <p>Shipping Address: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>Kind Regards,</p>
                            <p>{app_name}</p>',
                    'ja' => '<p>Dear、 {salesorder_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>新しい引用符が割り当てられています。</p>
                            <p>見積もり番号 : {quote_number}</p>
                            <p>請求先住所 : {billing_address}</p>
                            <p>出荷先住所 : {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>カンド・リーカード</p>
                            <p>{app_name}</p>',
                    'nl' => '<p>Geachte, { salesorder_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>U bent toegewezen aan een nieuwe offerte:</p>
                            <p>Quote-nummer: { quote_number }</p>
                            <p>Factureringsadres: { billing_address }</p>
                            <p>Verzendadres: { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Vriendelijke groeten,</p>
                            <p>{ app_name }</p>',
                    'pl' => '<p>Szanowny, {salesorder_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Użytkownik został przypisany do nowego notowania:</p>
                            <p>Numer oferty: {quote_number }</p>
                            <p>Adres do faktury: {billing_address }</p>
                            <p>Adres dostawy: {shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>W Odniesieniu Do Rodzaju,</p>
                            <p>{app_name }</p>',
                    'ru' => '<p>Уважаемый, { salesorder_assign_user }</p>
                            <p>&nbsp;</p>
                            <p>Вам назначено новое предложение:</p>
                            <p>Номер предложения: { quote_number }</p>
                            <p>Адрес выставления счета: { billing_address }</p>
                            <p>Адрес доставки: { shipping_address }</p>
                            <p>&nbsp;</p>
                            <p>Привет.</p>
                            <p>{ имя_программы }</p>',
                    'pt' => '<p>Querido, {salesorder_assign_user}</p>
                            <p>&nbsp;</p>
                            <p>Voc&ecirc; foi designado para uma nova cita&ccedil;&atilde;o:</p>
                            <p>N&uacute;mero da Cota&ccedil;&atilde;o: {quote_number}</p>
                            <p>Endere&ccedil;o de Faturamento: {billing_address}</p>
                            <p>Endere&ccedil;o de Navega&ccedil;&atilde;o: {shipping_address}</p>
                            <p>&nbsp;</p>
                            <p>Esp&eacute;cie Considera,</p>
                            <p>{app_name}</p>',
                ],
            ],
            'New Sales Invoice' => [
                'subject' => 'Sales Invoice Create',
                'variables' => '{
                    "Sales Invoice Number": "invoice_id",
                    "Sales Invoice Client": "invoice_client",
                    "Sales Invoice Status": "invoice_status",
                    "Sales Invoice Total": "invoice_sub_total",
                    "Sales Invoice Issue Date": "created_at",
                    "App Url": "app_url",
                    "App Name": "app_name"
                  }',
                'lang' => [
                    'ar' => 'العزيز<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span><br><br>لقد قمنا بإعداد الفاتورة التالية من أجلك<span style="font-size: 12pt;">: </span><strong style="font-size: 12pt;">&nbsp;{invoice_id}</strong><br><br>حالة الفاتورة<span style="font-size: 12pt;">: {invoice_status}</span><br><br><br>يرجى الاتصال بنا للحصول على مزيد من المعلومات<span style="font-size: 12pt;">.</span><br><br>أطيب التحيات<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'da' => 'Kære<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span><br><br>Vi har udarbejdet følgende faktura til dig<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span><br><br>Fakturastatus: {invoice_status}<br><br>Kontakt os for mere information<span style="font-size: 12pt;">.</span><br><br>Med venlig hilsen<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'de' => '<p><b>sehr geehrter</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><br><br>Wir haben die folgende Rechnung für Sie vorbereitet<span style="font-size: 12pt;">: {invoice_id}</span><br><br><b>Rechnungsstatus</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Bitte kontaktieren Sie uns für weitere Informationen<span style="font-size: 12pt;">.</span><br><br><b>Mit freundlichen Grüßen</b><span style="font-size: 12pt;">,</span><br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><strong>Dear</strong>&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p>
                            <p><span style="font-size: 12pt;">We have prepared the following invoice for you :#{invoice_id}</span></p>
                            <p><span style="font-size: 12pt;"><strong>Invoice Status</strong> : {invoice_status}</span></p>
                            <p>Please Contact us for more information.</p>
                            <p><span style="font-size: 12pt;">&nbsp;</span></p>
                            <p><strong>Kind Regards</strong>,<br /><span style="font-size: 12pt;">{app_name}</span></p>',
                    'es' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Hemos preparado la siguiente factura para ti<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Estado de la factura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Por favor contáctenos para más información<span style="font-size: 12pt;">.</span></p><p><b>Saludos cordiales</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'fr' => '<p><b>Cher</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Nous avons préparé la facture suivante pour vous<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>État de la facture</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Veuillez nous contacter pour plus d\'informations<span style="font-size: 12pt;">.</span></p><p><b>Sincères amitiés</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'it' => '<p><b>Caro</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Abbiamo preparato per te la seguente fattura<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Stato della fattura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Vi preghiamo di contattarci per ulteriori informazioni<span style="font-size: 12pt;">.</span></p><p><b>Cordiali saluti</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'ja' => '親愛な<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span><br><br>以下の請求書をご用意しております。<span style="font-size: 12pt;">: {invoice_client}</span><br><br>請求書のステータス<span style="font-size: 12pt;">: {invoice_status}</span><br><br>詳しくはお問い合わせください<span style="font-size: 12pt;">.</span><br><br>敬具<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'nl' => '<p><b>Lieve</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>We hebben de volgende factuur voor u opgesteld<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Factuurstatus</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Voor meer informatie kunt u contact met ons opnemen<span style="font-size: 12pt;">.</span></p><p><b>Vriendelijke groeten</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pl' => '<p><b>Drogi</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Przygotowaliśmy dla Ciebie następującą fakturę<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status faktury</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Skontaktuj się z nami, aby uzyskać więcej informacji<span style="font-size: 12pt;">.</span></p><p><b>Z poważaniem</b><span style="font-size: 12pt;"><b>,</b><br></span>{app_name}</p>',
                    'ru' => '<p><b>дорогая</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Мы подготовили для вас следующий счет<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Статус счета</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Пожалуйста, свяжитесь с нами для получения дополнительной информации<span style="font-size: 12pt;">.</span></p><p><b>С уважением</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pt' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Preparamos a seguinte fatura para você<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status da fatura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Entre em contato conosco para mais informações.<span style="font-size: 12pt;">.</span></p><p><b>Atenciosamente</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                ],
            ],
            'Sales Invoice Sent' => [
                'subject' => 'Sales Invoice Send',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Invoice ReciverName": "invoice_recivername",
                    "Invoice Number": "salesinvoice_number",
                    "Invoice Url": "salesinvoice_url"
                  }',
                  'lang' => [
                    'ar' => '<p>مرحبا ، { invoice_recivername }</p>
                    <p>مرحبا بك في { app_name }</p>
                    <p>أتمنى أن يجدك هذا البريد الإلكتروني جيدا برجاء الرجوع الى رقم الفاتورة الملحقة { salesinvoice_number } للخدمة / الخدمة.</p>
                    <p>ببساطة اضغط على الاختيار بأسفل.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">المبيعات القذرة</strong> </a></span></p>
                    <p>إشعر بالحرية للوصول إلى الخارج إذا عندك أي أسئلة.</p>
                    <p>شكرا لك</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'da' => '<p>Hej, { invoice_recivername }</p>
                    <p>Velkommen til { app_name }</p>
                    <p>H&aring;ber denne e-mail finder dig godt! Se vedlagte Beskidt salgnummer { salesinvoice_number } for product/service.</p>
                    <p>Klik p&aring; knappen nedenfor.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Beskidt salg</strong> </a></span></p>
                    <p>Du er velkommen til at r&aelig;kke ud, hvis du har nogen sp&oslash;rgsm&aring;l.</p>
                    <p>Tak.</p>
                    <p>&nbsp;</p>
                    <p>Med venlig hilsen</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'de' => '<p>Hi, {invoice_recivername}</p>
                    <p>Willkommen bei {app_name}</p>
                    <p>Hoffe, diese E-Mail findet dich gut! Bitte beachten Sie die beigef&uuml;gte schmutzigenummer {salesinvoice_number} f&uuml;r Produkt/Service.</p>
                    <p>Klicken Sie einfach auf den Button unten.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">schmutzige</strong> </a></span></p>
                    <p>F&uuml;hlen Sie sich frei, wenn Sie Fragen haben.</p>
                    <p>Vielen Dank,</p>
                    <p>&nbsp;</p>
                    <p>Betrachtet,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'en' => '<p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Hi, {invoice_recivername}</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Welcome to {app_name}</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Hope this email finds you well! Please see attached sales invoice number {salesinvoice_number} for product/service.</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Simply click on the button below.</span></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">sales invoice</strong> </a></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Feel free to reach out if you have any questions.</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Thank You,</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">Regards,</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">{company_name}</span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif; font-size: 15px; font-variant-ligatures: common-ligatures; background-color: #f8f8f8;">{app_url}</span></p>',
                    'es' => '<p>Hi, {invoice_recivername}</p>
                    <p>&nbsp;</p>
                    <p>Bienvenido a {app_name}</p>+
                    <p>&nbsp;</p>
                    <p>&iexcl;Espero que este email le encuentre bien! Consulte el n&uacute;mero de factura de ventas adjunto {salesinvoice_number} para el producto/servicio.</p>
                    <p>&nbsp;</p>
                    <p>Simplemente haga clic en el bot&oacute;n de abajo.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">factura de ventas</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Si&eacute;ntase libre de llegar si usted tiene alguna pregunta.</p>
                    <p>&nbsp;</p>
                    <p>Gracias,</p>
                    <p>&nbsp;</p>
                    <p>Considerando,</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Bonjour, { invoice_recivername }</p>
                    <p>&nbsp;</p>
                    <p>Bienvenue dans { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Jesp&egrave;re que ce courriel vous trouve bien ! Voir le num&eacute;ro de Facture de vente { salesinvoice_number } pour le produit/service.</p>
                    <p>&nbsp;</p>
                    <p>Cliquez simplement sur le bouton ci-dessous.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Facture de vente</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Nh&eacute;sitez pas &agrave; nous contacter si vous avez des questions.</p>
                    <p>&nbsp;</p>
                    <p>Merci,</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'it' => '<p>Ciao, {invoice_recivername}</p>
                    <p>&nbsp;</p>
                    <p>Benvenuti in {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Spero che questa email ti trovi bene! Si prega di consultare il numero di fattura di vendita collegato {salesinvoice_number} per il prodotto/servizio.</p>
                    <p>&nbsp;</p>
                    <p>Semplicemente clicca sul pulsante sottostante.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">fattura di vendita</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Sentiti libero di raggiungere se hai domande.</p>
                    <p>&nbsp;</p>
                    <p>Grazie,</p>
                    <p>&nbsp;</p>
                    <p>Riguardo,</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは、 {invoice_recivername}</p>
                    <p>&nbsp;</p>
                    <p>{app_name} へようこそ</p>
                    <p>&nbsp;</p>
                    <p>この E メールでよくご確認ください。 製品 / サービスについては、添付された請求書番号 {salesinvoice_number} を参照してください。</p>
                    <p>&nbsp;</p>
                    <p>以下のボタンをクリックしてください。</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">販売請求書</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>質問がある場合は、自由に連絡してください。</p>
                    <p>&nbsp;</p>
                    <p>ありがとうございます</p>
                    <p>&nbsp;</p>
                    <p>よろしく</p>
                    <p>&nbsp;</p>
                    <p>{ company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo, { invoice_recivername }</p>
                    <p>Welkom bij { app_name }</p>
                    <p>Hoop dat deze e-mail je goed vindt! Zie bijgevoegde Verkoopfactuurnummer { salesinvoice_number } voor product/service.</p>
                    <p>Klik gewoon op de knop hieronder.</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Verkoopfactuur</strong> </a></span></p>
                    <p>Voel je vrij om uit te reiken als je vragen hebt.</p>
                    <p>Dank U,</p>
                    <p>Betreft:</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'pl' => '<p>Witaj, {invoice_recivername }</p>
                    <p>&nbsp;</p>
                    <p>Witamy w aplikacji {app_name }</p>
                    <p>&nbsp;</p>
                    <p>Mam nadzieję, że ta wiadomość znajdzie Cię dobrze! Sprawdź załączoną faktura sprzedaży numer {salesinvoice_number } dla produktu/usługi.</p>
                    <p>&nbsp;</p>
                    <p>Wystarczy kliknąć na przycisk poniżej.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">faktura sprzedaży</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Czuj się swobodnie, jeśli masz jakieś pytania.</p>
                    <p>&nbsp;</p>
                    <p>Dziękuję,</p>
                    <p>&nbsp;</p>
                    <p>W odniesieniu do</p>
                    <p>&nbsp;</p>
                    <p>{company_name }</p>
                    <p>&nbsp;</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Привет, { invoice_recivername }</p>
                    <p>&nbsp;</p>
                    <p>Вас приветствует { app_name }</p>
                    <p>&nbsp;</p>
                    <p>Надеюсь, это электронное письмо найдет вас хорошо! См. вложенный номер счета-фактуры { salesinvoice_number } для производства/услуги.</p>
                    <p>&nbsp;</p>
                    <p>Просто нажмите на кнопку внизу.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">накладная продаж</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Не стеснитесь, если у вас есть вопросы.</p>
                    <p>&nbsp;</p>
                    <p>Спасибо.</p>
                    <p>&nbsp;</p>
                    <p>С уважением,</p>
                    <p>&nbsp;</p>
                    <p>{ company_name }</p>
                    <p>&nbsp;</p>
                    <p>{ app_url }</p>',
                    'pt' => '<p>Oi, {invoice_recivername}</p>
                    <p>&nbsp;</p>
                    <p>Bem-vindo a {app_name}</p>
                    <p>&nbsp;</p>
                    <p>Espero que este e-mail encontre voc&ecirc; bem! Por favor, consulte o n&uacute;mero da factura de vendas anexa {salesinvoice_number} para produto/servi&ccedil;o.</p>
                    <p>&nbsp;</p>
                    <p>Basta clicar no bot&atilde;o abaixo.</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{salesinvoice_url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">factura de vendas</strong> </a></span></p>
                    <p>&nbsp;</p>
                    <p>Sinta-se &agrave; vontade para alcan&ccedil;ar fora se voc&ecirc; tiver alguma d&uacute;vida.</p>
                    <p>&nbsp;</p>
                    <p>Obrigado,</p>
                    <p>&nbsp;</p>
                    <p>Considera,</p>
                    <p>&nbsp;</p>
                    <p>{company_name}</p>
                    <p>&nbsp;</p>
                    <p>{app_url}</p>',
                ],
            ],
        ];

        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','Sales')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'Sales',
                        'module_name' => 'Sales',
                        'created_by' => 1,
                        'workspace_id' => 0
                    ]
                );
                foreach($defaultTemplate[$eTemp]['lang'] as $lang => $content)
                {
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $emailtemplate->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$eTemp]['subject'],
                            'variables' => $defaultTemplate[$eTemp]['variables'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }

    }
}
