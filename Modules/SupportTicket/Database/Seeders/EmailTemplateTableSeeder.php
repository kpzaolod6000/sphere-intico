<?php

namespace Modules\SupportTicket\Database\Seeders;

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
        $emailTemplate = [
                'New Ticket',
                'New Ticket Reply',
              ];

                $defaultTemplate = [
                        'New Ticket' => [
                                'subject' => 'New Ticket',
                                'variables' => '{
                                        "App Name": "app_name",
                                        "App Url": "app_url",
                                        "Ticket Name": "ticket_name",
                                        "Email": "email",
                                        "Ticket Id" : "ticket_id",
                                        "Password": "password",
                                        "Ticket Url": "ticket_url"
                                  }',
                                  'lang' => [
                                        'ar' => '<p>مرحبا ب<br />في { app_name }</p><p><strong>Email </strong><strong><br /><strong> <p><strong><strong>بطاقة طلب الخدمة </strong>: { ticket_id }<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "الخلفية : #6676ef; اللون : #ffffffSans Sans", Helvetica, Arial, Arial-weight : find ; line-الارتفاع : 120% ; الهامش : نص-تنسيق النص : none ; text-transform : none ; text-transform : لا شيء ؛ "href =" { ticket_url } "الهدف = "_blank" relate="noopener"> <strong style="color: white; font-weight: bold; text: white;">التحقق من التذكرة الخاصة بك</strong> </a> </a></span></p> <p>{ app_url }</p><p>شكرا ،<br />{ app_name }</p>',
                                        'da' => '<p>Hej, &nbsp;<br />Velkommen til { app_name }</p><p><strong>E-mail </strong>: { email }<br /><strong> <p><strong>Ticket-id </strong>: { ticket_id }<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "background: #6676ef; color: #ffffff; color: #ffffff; font-weight:" Open Sans ", Helvetica, Arial, sans-20:120%, margen: 0px; tekstdekoration: none ; teksttransformation: none ; teksttransformation: none ; "href =" { ticket_url } "mål = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Tjek din ticket</strong> </a></span></p></p> <p>{ app_url }</p><p>Tak,<br />{ app_name }</p>',
                                        'de' => '<p>Hello, &nbsp;<br />Welcome to {app_name}</p><p><strong>Email </strong>: {email}<br /><strong> <p><strong>Ticket-ID </strong>: {ticket_id}<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "background: #6676ef; color: #ffffff; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none; "href =" {ticket_url} "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Ticket überprüfen</strong> </a></span></p></p> <p>{app_url}</p><p>Danke,<br />{app_name}</p>',
                                        'en' => '<p>Welcome<br />to {app_name}</p><p><strong>Email </strong><strong><br /><strong><strong> <p><strong><strong><strong> </strong>: {ticket_id}<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style = "Background: #6676ef; Color: #ffffffSans Sans", Helvetica, Arial, Arial-weight: find; line-height: 120 %; Margin: Text-Formatting Text: none; text-transform: none; text-transform: None; "href =" {ticket_url} "Objective =" _blank " relate="noopener"> <strong style="color: white; font-weight: bold; text: white;">Validating your</strong></strong> </a></span></span></p> <p>{app_url}</p><p>Thanks,<br />{app_name}</p>',
                                        'es' => '<p>Hola, &nbsp;<br />Bienvenido a {app_name}</p><p><strong>Email </strong>: {email}<br /><strong> <p><strong><strong>Ticket ID </strong>: {ticket_id}<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "background: #6676ef; color: #ffffff; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none; "href =" {ticket_url} "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Comprobar su ticket</strong> </a> </a></p></p> <p>{app_url}</p><p>Gracias,<br />{app_name}</p>',
                                        'fr' => '<p>Bonjour, &nbsp;<br />Bienvenue dans { app_name }</p><p><strong>Email </strong>: { email }<br /><strong><strong> <p><strong><strong>ID </strong>: { ticket_id }<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "background: #6676ef; color: #ffffff; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-weight: normal ; line-height: 120% ; margin: 0px; text-decoration: none ; text-transform: None ; "href =" { ticket_url } "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Vérifiez votre</strong></strong> </a></span></span></p> <p>{ app_url }</p><p>Merci,<br />{ app_name }</p>',
                                        'it' => '<p>Ciao, &nbsp;<br />Benvenuti in {app_name}</p><p><strong>Email </strong>: {email}<br /><strong> <p><strong>Ticket ID </strong>: {ticket_id}<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "sfondo: #6676ef; colore: #ffffff; font - famiglia:" Open Sans ", Helvetica, Arial, sans - serif; font - peso: normale; linea - altezza: 120%; margine: 0px; testo - decorazione: nessuno; testo - trasformazione: none; "href =" {ticket_url} "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Verificare il tuo biglietto</strong> </a></span></p> <p>{app_url}</p><p>Grazie,<br />{app_name}</p>',
                                        'ja' => '<p>&nbsp; app_name}</p><br /><p><strong>E メール </strong>: {email </strong>: {email </strong><br /><strong><strong><strong><strong><strong>ID </strong>: {ticket_id}<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="バックグラウンド: #6676ef; カラー: #ffffff; カラー : #ffffff; フォント・ファミリー: フォント・ファミリー: フォント・ファミリー: 「オープン・サンズ」、ヘルプ・ファミリー。フォント・ウェイト: フォント・ファミリー : 120%: 0px; テキスト装飾: なし; テキスト変換: なし。 none;" href="{ticket_url}" ターゲット=" _blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">チケット</strong> 確認します </a> </a></span></span></p> <p>{app_url}</p><p>ありがとう、<br />{app_name}</p>',
                                        'nl' => '<p>Hallo, &nbsp;<br />Welkom bij { app_name }</p><p><strong>E-mail </strong>: { email }:<strong>
                                        <p><strong>Ticket-ID </strong>: { ticket_id </strong>: { ticket_id }<br />
                                        <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "achtergrond: #6676ef; kleur: #ffffff; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-gewicht: normaal; lijn-hoogte: 120%; margin: 0px; tekst-decoratie: geen; tekstconversie: none; "href =" { ticket_url } "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Uw ticket controleren</strong> </a></span></p>
                                        <p>{ app_url }</p><p>Bedankt,<br />{ app_name }</p>',
                                        'pl' => '<p>Hello, &nbsp;<br />Welcome to {app_name }</p><p><strong>Email </strong>: {email }<br /><strong> <p><strong>ID zgłoszenia </strong>: {ticket_id }<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "background: #6676ef; color: #ffffff; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none; "href =" {ticket_url } "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Sprawdź</strong></strong> </a></span></span></p> <p>{app_url }</p><p>Dzięki,<br />{app_name }</p>',
                                        'ru' => '<p>Здравствуйте, &nbsp;<br />Добро пожаловать в { app_name }</p><p><strong>Электронная почта </strong>: { email }<br /><strong>
                                        <p><strong>ID тикета </strong>: { ticket_id }<br />
                                        <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "backgrows: #6676ef; color: #ffffff; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none; "href =" { ticket_url } "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Проверить паспорт</strong> </a></span></p>
                                        <p><p>{ app_url }</p><p>Спасибо,<br />{ app_name }</p>',
                                        'pt' => '<p>Olá, &nbsp;<br />Bem-vindo a {app_name}</p><p><strong>Email </strong>: {email}<br /><strong><strong><strong>Ticket ID </strong>: {ticket_id}<br /> <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style= "background: #6676ef; font-family:" Open Sans ", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none; "href =" {ticket_url} "target = "_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Confira o seu ticket</strong> </a></span></p> <p>{app_url}</p><p>Obrigado,<br />{app_name}</p>',
                                ],
                            ],
                            'New Ticket Reply' => [
                                'subject' => 'Ticket Reply',
                                'variables' => '{
                                        "App Name" : "app_name",
                                        "Company Name" : "company_name",
                                        "App Url": "app_url",
                                        "Ticket Name" : "ticket_name",
                                        "Ticket Id" : "ticket_id",
                                        "Ticket Description" : "reply_description"
                                    }',
                                'lang' => [
                                    'ar' => '<p>مرحبا ، مرحبا بك في { app_name }.</p><p>&nbsp;</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p>&nbsp;</p><p>الوصف : { reply_description }</p><p>&nbsp;</p><p>شكرا</p><p>{ app_name }</p>',
                                    'da' => '<p>Hej, velkommen til { app_name }.</p><p>&nbsp;</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p>&nbsp;</p><p>Beskrivelse: { reply_description }</p><p>&nbsp;</p><p>Tak.</p><p>{ app_name }</p>',
                                    'de' => '<p>Hallo, Willkommen bei {app_name}.</p><p>&nbsp;</p><p>{ticketname}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Beschreibung: {reply_description}</p><p>&nbsp;</p><p>Danke,</p><p>{Anwendungsname}</p>',
                                    'en' => '<p>Hello,&nbsp;<br />Welcome to {app_name}.</p><p>{ticket_name}</p><p>{ticket_id}</p><p><strong>Description</strong> : {reply_description}</p><p>Thanks,<br />{app_name}</p>',
                                    'es' => '<p>Hola, Bienvenido a {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descripci&oacute;n: {reply_description}</p><p>&nbsp;</p><p>Gracias,</p><p>{app_name}</p>',
                                    'fr' => '<p>Hola, Bienvenido a {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descripci&oacute;n: {reply_description}</p><p>&nbsp;</p><p>Gracias,</p><p>{app_name}</p>',
                                    'it' => '<p>Ciao, Benvenuti in {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descrizione: {reply_description}</p><p>&nbsp;</p><p>Grazie,</p><p>{app_name}</p>',
                                    'ja' => '<p>こんにちは、 {app_name}へようこそ。</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>説明 : {reply_description}</p><p>&nbsp;</p><p>ありがとう。</p><p>{app_name}</p>',
                                    'nl' => '<p>Hallo, Welkom bij { app_name }.</p><p>&nbsp;</p><p>{ ticket_name }</p><p>{ ticket_id }</p><p>&nbsp;</p><p>Beschrijving: { reply_description }</p><p>&nbsp;</p><p>Bedankt.</p><p>{ app_name }</p>',
                                    'pl' => '<p>Witaj, Witamy w aplikacji {app_name }.</p><p>&nbsp;</p><p>{ticket_name }</p><p>{ticket_id }</p><p>&nbsp;</p><p>Opis: {reply_description }</p><p>&nbsp;</p><p>Dziękuję,</p><p>{app_name }</p>',
                                    'ru' => '<p>Здравствуйте, Добро пожаловать в { app_name }.</p><p>&nbsp;</p><p>Witaj, Witamy w aplikacji {app_name }.</p><p>&nbsp;</p><p>{ticket_name }</p><p>{ticket_id }</p><p>&nbsp;</p><p>Opis: {reply_description }</p><p>&nbsp;</p><p>Dziękuję,</p><p>{app_name }</p>',
                                    'pt' => '<p>Ol&aacute;, Bem-vindo a {app_name}.</p><p>&nbsp;</p><p>{ticket_name}</p><p>{ticket_id}</p><p>&nbsp;</p><p>Descri&ccedil;&atilde;o: {reply_description}</p><p>&nbsp;</p><p>Obrigado,</p><p>{app_name}</p>',
                                ],
                            ],
                ];

                foreach($emailTemplate as $eTemp)
                {
                    $table = EmailTemplate::where('name',$eTemp)->where('module_name','SupportTicket')->exists();
                        if(!$table)
                        {
                                $emailtemplate=  EmailTemplate::create(
                                [
                                        'name' => $eTemp,
                                        'from' => 'SupportTicket',
                                        'module_name' => 'SupportTicket',
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
