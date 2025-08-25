<div class=" pt-3 md:pt-12 bg-[#f8f2e8f2] antialiased" wire:loading.class="cursor-wait"  x-data="{ selectedTab: 'userAgb' }">
    <x-slot name="header">
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
                Allgemeine Geschäftsbedingungen (AGB) 
              <svg width="80px" class="aspect-square text-[#333] ml-10  inline opacity-30" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
              </svg>  
            </h1>
    </x-slot>
    <div class="max-w-7xl mx-auto px-5 pb-12">
        <div class="bg-white shadow-lg rounded-lg p-6 md:p-10">
        <!-- Tab-Menü -->
        <ul class="flex w-full text-sm font-medium text-center text-gray-500 bg-gray-100 rounded-lg shadow divide-gray-200">
                <!-- Details Tab -->
                <li class="w-full">
                    <button 
                        @click="selectedTab = 'userAgb'" 
                        :class="{ 'text-blue-600 bg-gray-100 border-b-2 border-blue-600': selectedTab === 'userAgb' }" 
                        class="w-full p-4 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
                    >
                        Allgemeine AGB's
                    </button>
                </li>
                
                <!-- Buchungen Tab -->
                <li class="w-full border-l border-gray-200">
                    <button 
                        @click="selectedTab = 'sellerAgb'" 
                        :class="{ 'text-blue-600 bg-gray-100 border-b-2 border-blue-600': selectedTab === 'sellerAgb' }" 
                        class="w-full p-4 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
                    >
                        Anbieter AGB's
                    </button>
                </li>
            </ul>


        <div>
            <div  x-show="selectedTab === 'userAgb'" x-collapse  x-cloak>
                <div class="w-full py-10">
                    <p><strong>Allgemeine Geschäftsbedingungen für Nutzer von MiniFinds</strong></p>
                    <p>Unsere AGB gelten bei der Nutzung des Portals MiniFinds auf unseren Webseiten oder Apps, auf Profilen auf fremden Webseiten oder Apps sowie auf allen anderen Vertriebswegen („Plattformen“) als vereinbart. Insbesondere enthalten sie Ihre Rechte und Pflichten beim Auffinden und der Inanspruchnahme von Leistungen von Anbietern auf dem Gebiet des Erwerbs von Second-Hand Kinderkleidung, -zubehör und Spielzeugen sowie die wichtigsten Datenschutzbestimmungen.</p>
                    <p>Die AGB für Anbieter von Leistungen auf unseren Plattformen finden Sie <a class="transition-all duration-200 text-blue-500 hover:text-blue-700 focus:outline-none" @click="selectedTab = 'sellerAgb'" >hier.</a></p>
                    <p><strong>Präambel MiniFinds</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <p>Minifinds ist der Second-Hand-Shop für Kinderkleidung, -zubehör und Spielzeugen:</p>
                    <ul>
                    <li>Bequem, stressfrei und</li>
                    <li>Nachhaltig - gib gut erhaltener Kinderkleidung und Spielzeugen eine neue Chance.</li>
                    </ul>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 1 Geltungsbereich</strong></li>
                    </ul>
                    <p>(1) Diese Allgemeinen Geschäftsbedingungen (im Folgenden: „AGB“) gelten für alle</p>
                    <p>Vertragsverhältnisse zur Regelung der Nutzung unserer Plattformen</p>
                    <p>zwischen</p>
                    <p>MiniFinds eGbR</p>
                    <p>Schwarzbuchenweg 49</p>
                    <p>22391 Hamburg,</p>
                    <p>(mehr Informationen im Impressum: <a href="https://www.minifinds.de/imprint"></a><a href="https://www.minifinds.de/imprint">https://www.minifinds.de/imprint</a>)</p>
                    <p>(nachfolgend „Verwender“, „wir“, oder „uns“ genannt)</p>
                    <p>und Ihnen</p>
                    <p>(nachfolgend „Nutzer“, „Partner“, „Ihr“ oder „Sie“ genannt).</p>
                    <p>(2) Die AGB gelten unabhängig davon, ob Sie Verbraucher oder Unternehmer sind.</p>
                    <p>(3) “Verbraucher” sind Sie, wenn Sie eine natürliche Person sind, die einen Vertrag mit uns zu Zwecken abschließt, der überwiegend weder Ihrer gewerblichen noch Ihrer selbständigen, freiberuflichen, öffentlich-rechtlichen oder gemeinnützigen beruflichen Tätigkeit zugerechnet werden kann.</p>
                    <p>(4) “Unternehmer”, sind Sie, wenn Sie eine natürliche Person sind oder einen Vertragsschluss für eine juristische Person oder eine rechtsfähige Personengesellschaft tätigen, die bei Abschluss des Vertrages mit uns in Ausübung ihrer gewerblichen, freiberuflichen, selbständigen, öffentlich-rechtlichen oder gemeinnützigen beruflichen Tätigkeit handelt.</p>
                    <p>(5) Diese AGB gelten auf allen unseren Plattformen. “Plattformen” sind alle unsere Vertriebs- und Operationskanäle und -dienste. Insbesondere sind es unsere Webseiten oder Apps und unsere Profile auf Webseiten oder Apps unserer Partner. Insbesondere sind es alle unsere Räumlichkeiten; alle unsere physischen oder elektronischen Unterlagen wie E-Mails, Auftragsdokumente oder Informationsmaterialien; alle unsere Vertriebsflächen sowie unsere Webseiten oder Apps und unsere Profile auf Webseiten oder Apps unserer Partner.</p>
                    <p>(6) Maßgebend ist die jeweils bei Abschluss des Vertrags gültige Fassung der AGB. Abweichende AGB werden nicht akzeptiert. Dies gilt auch, wenn wir der Einbeziehung nicht ausdrücklich widersprochen haben. Etwas anderes kann gelten, soweit in diesen AGB in Einzelfällen etwas anderes bestimmt ist. Soweit in eine andere Sprache als Deutsch übersetzte Rechtstexte oder Dokumente bestehen, sind die deutschen Rechtstexte oder Dokumente rechtlich verbindlich und damit anwendbar – die übersetzten Rechtstexte oder Dokumente dienen alleine zum besseren Verständnis.</p>
                    <p>(7) Alle zwischen Ihnen und uns im Zusammenhang mit einer Leistung getroffenen Vereinbarungen ergeben sich insbesondere aus einer Bestellung bzw. Beauftragung sowie den dazugehörigen Anlagen, unserer Bestätigung, unserer Annahme sowie ergänzend, soweit dort nicht geregelt, aus diesen AGB. Diese AGB finden auch auf spätere Bestellungen bzw. Beauftragungen Anwendung, die Sie während oder nach Ablauf der Vertragslaufzeit abgeben, es sei denn, zu diesem Zeitpunkt sind andere AGB einbezogen worden.</p>
                    <p>(8) Mit Ausnahme schriftlicher Änderungen und Ergänzungen dieser AGB werden elektronisch oder digital erstellte Dokumente oder Unterlagen schriftlichen Dokumenten oder Unterlagen gleichgestellt.</p>
                    <p>(9) Diese AGB gelten auch für andere, zwischen Ihnen und uns geschlossenen Verträge, soweit keine speziellen, auf die andere Vertragsart bezogenen AGB vorliegen und Klauseln dieser AGB inhaltlich Anwendung finden können.</p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 2 Vertragsschluss</strong></li>
                    </ul>
                    <p>(1) Die Leistungen auf unseren Plattformen können ohne ein Konto in Anspruch genommen werden. Sie geben mit der Nutzung unserer Plattformen ein Angebot auf Abschluss eines Vertrages für die Dauer der Nutzung unserer Plattformen gemäß dieser AGB ab, welches wir durch die Erbringung der Leistung annehmen.</p>
                    <ul>
                    <li><strong> 3 Preise, Zahlung, Verzug, Zahlungsbedingungen, Aufrechnung, Zurückbehaltungsrecht</strong></li>
                    </ul>
                    <p>(1) Für unsere kostenpflichtigen Leistungen auf unseren Plattformen gilt: Die von uns angegebenen Preise sind – soweit im Einzelfall nichts anderes präsentiert oder vereinbart wurde– Bruttopreise inklusive der Umsatzsteuer. Die Preise gelten – soweit im Einzelfall nicht anders vereinbart wurde – zuzüglich anfallender Lieferkosten, Versandkosten und Montagekosten.</p>
                    <p>(2) Unsere Vergütung wird – soweit keine andere Vereinbarung zwischen Ihnen und uns besteht – nach Abschluss des Vertrages und vor der jeweiligen Leistungserbringung fällig. Sie ist spätestens innerhalb von 2 Wochen nach Versand unserer Rechnung (Rechnungsdatum) zu bezahlen. Unterbleibt die Zahlung, tritt Zahlungsverzug ein. Bei Zahlungsverzug sind wir berechtigt, nach den gesetzlichen Bestimmungen Verzugszinsen und weiteren Schadensersatz geltend zu machen. Der Verzugszins gegenüber Verbrauchern beträgt für das Jahr 5 Prozentpunkte über dem Basiszinssatz nach § 288 BGB; gegenüber Unternehmern beträgt der Verzugszins für das Jahr 9 Prozentpunkte über dem Basiszinssatz nach § 288 BGB.</p>
                    <p>(3) Wir ermöglichen Ihnen die Nutzung verschiedener Zahlungsdienste und -möglichkeiten. Sie können zur Zahlung jeden von uns bereitgestellten Zahlungsweg nutzen, insbesondere</p>
                    <ul>
                    <li>auf ein von uns angegebenes Konto überweisen,</li>
                    <li>uns eine Einzugsermächtigung oder SEPA-Lastschriftmandat erteilen,</li>
                    <li>uns per EC-/Maestro- oder Kreditkarte bezahlen,</li>
                    <li>uns über eine Plattform Dritter bezahlen (beispielsweise Apple App Store, Google Play oder Amazon Appstore),</li>
                    <li>oder uns über einen von uns angegebenen Zahlungsdienstleister (beispielsweise PayPal) bezahlen,</li>
                    </ul>
                    <p>jeweils, sofern wir eine entsprechende Zahlungsmöglichkeit anbieten. Wir behalten uns vor, Zahlungsmöglichkeiten individuell oder allgemein auszuschließen oder im Nachgang zu ergänzen.</p>
                    <p>(4) Sie nehmen die Zahlungsleistung eines Zahlungsdienstleisters in Anspruch, indem Sie auf den Button des Zahlungsdienstleisters während des Bestellprozesses von Leistungen klicken. Sie werden auf die entsprechende Seite des jeweiligen Zahlungsdienstleisters geführt. Sie nehmen die Zahlungsleistung einer dritten Plattform wie Apple App Store, Google Play oder Amazon Appstore in Anspruch, indem Sie unsere App über ihn runterladen. Wir stellen hinsichtlich der Zahlung nur den Zugang zur Seite des jeweiligen Zahlungsdienstleisters oder der Plattform bereit, werden aber nicht Vertragspartei. Meistens ist es zur Nutzung von Zahlungsdiensten eines Zahlungsdienstleisters oder der Plattform erforderlich, ein Vertragsverhältnis mit dem entsprechenden Zahlungsdienstleister einzugehen. Es gelten die jeweiligen Vertragsbedingungen, AGB und Datenschutzbestimmungen.</p>
                    <p>(5) Im Fall einer erteilten Einzugsermächtigung, eines SEPA-Lastschriftmandats oder der Zahlung per EC-/Maestro- oder Kreditkarte werden wir die Belastung Ihres Kontos frühestens zum Fälligkeitszeitpunkt veranlassen. Eine erteilte Einzugsermächtigung gilt bis auf Widerruf auch für weitere Aufträge.</p>
                    <p>(6) Sie sind nicht berechtigt, gegenüber unseren Forderungen aufzurechnen, es sei denn, Ihre Gegenansprüche sind rechtskräftig festgestellt oder unbestritten, sowie dann, wenn Sie Mängelrügen oder Gegenansprüche aus demselben Vertragsverhältnis geltend machen.</p>
                    <p>(7) Sie dürfen nur dann ein Zurückbehaltungsrecht ausüben, wenn Ihr Gegenanspruch aus demselben Vertragsverhältnis herrührt und rechtskräftig festgestellt oder unbestritten ist.</p>
                    <p>(8) Für den Fall, dass auf eine unserer Forderung aus einem oder mehreren Verträgen nicht fristgerecht gezahlt wird, sind wir berechtigt, ein Inkassobüro (z.B.&nbsp;Creditreform) mit dem weiteren Einzug der fälligen Forderung zu beauftragen. Sie willigen mit Vertragsschluss ein, dass wir die zum Einzug der Forderung erforderlichen Daten und Informationen an das Inkassobüro (z.B.&nbsp;Creditreform) übermitteln und das Inkassobüro (z.B. Creditreform) zur Speicherung und Verarbeitung der Daten berechtigt ist. Insbesondere werden Name und Anschrift, Vertragsdatum, sowie Rechnungsnummer, Rechnungsbetrag und das Fälligkeitsdatum übermittelt.</p>
                    <p>(9) Gebühren (jegliche Ämter, Behörden o. ä.), Honorare oder sonstige Zahlungsansprüche anderer aus der Leistungserbringung resultierender Zahlungssachverhalte sind nicht im Preis enthalten und werden von Ihnen gesondert und gegenüber den jeweiligen Stellen bzw. Personen entrichtet. Dies gilt auch dann, wenn diese Ausgaben durch uns vorausgelegt werden; sie sind in diesem Fall an uns zu erstatten.</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 4 Zahlungsdienstleister für Leistungen der Anbieter</strong></li>
                    </ul>
                    <p>(1) Wir können Ihnen für den Fall der Inanspruchnahme kostenpflichtiger Leistungen der Anbieter verschiedene Zahlungsdienste und -möglichkeiten über die Systeme unserer Plattformen zur Verfügung stellen, um eine reibungslose Zahlung der Vergütung des Anbieters zu erleichtern.</p>
                    <p>(2) Sie können unter verschiedenen Zahlungsdiensten und Zahlungsmöglichkeiten wählen. Wir behalten uns vor, bestimmte Zahlungsmöglichkeiten individuell auszuschließen oder nachträglich zu ergänzen.</p>
                    <p>(3) Sie können die Zahlung im Rahmen des verbindlichen Bestellvorgangs von Leistungen durch den Klick auf den entsprechenden Button des Zahlungsdienstleisters einleiten. Sie werden dann auf die entsprechende Seite des jeweiligen Zahlungsdienstleisters weitergeleitet.</p>
                    <p>(4) Wir stellen nur den technischen Zugang zur Seite des jeweiligen Zahlungsdienstleisters her, werden aber nicht Vertragspartei im Rahmen des Zahlungsvorgangs. Die Nutzung von Zahlungsdiensten setzt grundsätzlich ein Vertragsverhältnis mit dem entsprechenden Zahlungsdienstleister voraus.</p>
                    <ul>
                    <li><strong> 5 Unsere Leistungen und Leistungen der Anbieter</strong></li>
                    </ul>
                    <p>(1) Wir präsentieren auf unseren Plattformen Leistungen von Anbietern (insbesondere von Händlern) auf dem Gebiet der Second-Hand-Shop für Kinderkleidung, -zubehör und Spielzeugen.</p>
                    <p>(2) Wir erbringen grundsätzlich selbst keine Leistungen auf diesen Gebieten. Wir übernehmen keine Haftung für Pflichtverletzungen oder Mängel aus den Verträgen zwischen Ihnen und den von uns präsentierten Anbietern, da wir in solchen Fällen alleine als Vermittler zwischen Ihnen und Anbieter tätig sind oder den Anbietern eine Präsentierfläche für ihre Leistungen bieten. Ausnahmsweise erbringen wir eine Leistung, falls Sie uns in einer besonderen Vereinbarung als Anbieter beauftragen. Dies ist nur der Fall, wenn ein individueller Vertragsschluss zwischen uns als Anbieter und Ihnen zur Durchführung einer solchen Leistung besteht.</p>
                    <p>(3) Um Ihnen das Auffinden passender Anbieter und Leistungen zu ermöglichen, stellen wir den Anbietern die Möglichkeit der Präsentation der relevanten Informationen (Bilder, Videos, Beschreibungen, Marken und Logos u.a.) zu spezifischen Anbietern oder ihren Angeboten zur Verfügung. Wir ermöglichen Ihnen, eine Suche durchzuführen und die Suchergebnisse nach diversen Kriterien zu sortieren oder sortieren die Ergebnisse vor. Wir behalten uns vor, Informationen zu verändern, um eine bessere Verständlichkeit sicherzustellen, insbesondere bei Inhalts-, Grammatik- oder Rechtschreibfehlern.</p>
                    <p>(4) Die redaktionellen Inhalte auf den Plattformen stellen ausdrücklich keine Beratung, insbesondere nicht im Einzelfall dar. Sie ersetzen keine fundierte Beratung und Betreuung im Einzelfall, wie sie von Anbietern durchgeführt wird.</p>
                    <p>(5) Für die Nutzung der Plattformen entstehen Ihnen keine Kosten.</p>
                    <p>(6) Wir ermöglichen Ihnen auf folgende Weisen, die von Anbietern angebotenen Leistungen über unsere Plattformen anzusehen und mit ihnen Verträge zu schließen:</p>
                    <ul>
                    <li>Marktplatzmodell: Der Nutzer nimmt eine vom Anbieter angebotene Leistung in Anspruch.</li>
                    </ul>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 6 Marktplatzmodell</strong></li>
                    </ul>
                    <p>(1) Anbieter präsentieren Nutzern auf der Plattform ihre Leistungen. Die alleinige Präsentation stellt kein bindendes Angebot des Anbieters zum Abschluss eines Vertrags mit dem Nutzer dar, sondern dient der unverbindlichen Darbietung.</p>
                    <p>(2) Angebote und Kostenvoranschläge des Anbieters an dem Nutzer auf der Plattform sind freibleibend. Kostenvoranschlags- und Angebotsfehler können vor der Auftragsannahme berichtigt werden.</p>
                    <p>(3) Eine rechtsverbindliche Bestellung bzw. Beauftragung können Sie ausschließlich in der Kanalstraße 14, 22085 Hamburg in den von uns genutzten Räumlichkeiten, Vertriebsflächen oder die von uns genutzten Kommunikationskanäle abgeben oder auf ein von uns ausgesprochenes Vertragsschlussangebot annehmend antworten.</p>
                    <p>(4) Sie stimmen mit der Beauftragung bzw. Bestellung zudem – soweit vorhanden – den AGB des Anbieters und der Datenverarbeitung gemäß dessen Datenschutzerklärung verbindlich zu.</p>
                    <p>(5) Sie sind gegenüber dem Anbieter an die Beauftragung bzw. Bestellung für die Dauer von 2 Wochen nach Abgabe der Bestellung gebunden.</p>
                    <p>(6) Der Anbieter selbst oder der Verwender im Namen des Anbieters können den Zugang der abgegebenen Bestellung bzw. Auftrages mündlich oder durch die Aushändigung einer Quittung oder jeglichen schriftlichen Bestätigung bestätigen. &nbsp;In einer solchen Bestätigung liegt noch keine verbindliche Annahme der Bestellung bzw. des Auftrages, es sei denn, darin wird neben der Bestätigung des Zugangs zugleich die Annahme erklärt.</p>
                    <p>(7) Die Bestätigung erfolgt grundsätzlich durch den Anbieter selbst oder durch uns im Namen des Anbieters, kann aber auch durch einen Dritten – wiederum unserem Namen als Vermittler des Anbieters erfolgen, insbesondere durch einen Vermittler oder ein Webportal, auf dem wir unsererseits ein Profil unterhalten, insbesondere wenn die Bestellung bzw. Beauftragung über das Webportal erfolgte.</p>
                    <p>(8) Ein Vertrag kommt erst zustande, wenn der Anbieter die Bestellung bzw. den Auftrag des Nutzers durch eine Annahmeerklärung annimmt, mit der Leistungserbringung beginnt, eine Rechnung stellt oder die Leistung – ganz oder teilweise – erbringt.</p>
                    <p>(9) Sollte die Erbringung der von Ihnen bestellten bzw. beauftragten Leistung nicht möglich sein, etwa, weil ein zur Erbringung erforderlicher Bestandteil der Leistung nicht erhältlich ist, sieht der Anbieter von einer Annahmeerklärung ab. In diesem Fall kommt ein Vertrag nicht zustande.</p>
                    <ul>
                    <li><strong> 7 Kostenfreiheit für Sie</strong></li>
                    </ul>
                    <p>(1) Für die Nutzung unserer Plattformen entstehen Ihnen keine Kosten.</p>
                    <p>(2) Alleine die Anbieter zahlen uns ein Honorar für unsere kostenpflichtigen Leistungen.</p>
                    <ul>
                    <li><strong> 8 Laufzeit und Kündigung</strong></li>
                    </ul>
                    <p>(1) Ein Vertrag zwischen Ihnen und uns läuft auf unbestimmte Zeit, soweit wir keine andere Laufzeit vereinbart haben.</p>
                    <p>(2) Ist keine Mindestlaufzeit vereinbart worden, sind Sie als auch wir jederzeit berechtigt, den Vertrag ohne Angabe von Gründen zu kündigen. Eine Kündigung kann per Mail, per Fax oder innerhalb des Kontos erfolgen. Bei einem entgeltlichen Dienst bleiben Sie trotz Kündigung zur Zahlung des vereinbarten Entgelts bis zum Vertragsende verpflichtet.</p>
                    <p>(3) Damit die Kündigung per E-Mail oder Fax zugeordnet werden kann, sollten der vollständige Name, die hinterlegte E-Mail-Adresse, die Anschrift und die persönliche Kennung werden.</p>
                    <p>(4)&nbsp;Im Falle einer Mindestvertragslaufzeit verlängert sich der Vertrag nach der Mindestvertragslaufzeit auf eine unbestimmte Zeit, wenn er nicht vorab mit einer Frist von einem Monat zum jeweiligen Laufzeitende im Voraus von einer der Parteien gekündigt wird. Nach der Verlängerung auf unbestimmte Zeit kann der Vertrag mit einer Frist von einem Monat zum Ende eines Monats gekündigt werden.</p>
                    <p>(5) Wir sind berechtigt, den Vertrag nach eigenem Ermessen, mit oder ohne vorherige Ankündigung und ohne Angabe von Gründen jederzeit und mit sofortiger Wirkung zu kündigen. Wir behalten uns daneben das Recht vor, Profile und/oder jeden Inhalt des Nutzers jederzeit zu entfernen. Falls die Registrierung des Nutzers beendet und/oder Profile oder veröffentlichte Inhalte des Nutzers entfernt wurden, besteht für uns keine Verpflichtung, den Nutzer darüber oder über den Grund der Kündigung und/oder der Entfernung eines Inhaltes zu informieren. Wir sind berechtigt, Informationen über die Kündigung an andere Nutzer – insbesondere, wenn sie Kontakt mit dem gekündigten Nutzer hatten – zu versenden.</p>
                    <p>(6) Jede Kündigungsart berechtigt uns zur Löschung des Kontos sowie aller von diesem erstellter bzw. hochgeladener persönlicher Daten. Personenbezogene Daten und andere Informationen, stehen in der alleinigen Verantwortung des jeweiligen Anbieters. Die Kündigung des Vertrags zwischen Verwender und Nutzer hat keine Auswirkung auf eine bereits abgeschlossene Vermittlung und das Leistungsverhältnis mit dem Anbieter, insbesondere wird es nicht rückgängig gemacht.</p>
                    <ul>
                    <li><strong> 9 Widerruf</strong></li>
                    </ul>
                    <p>(1) Falls Sie Unternehmer im Sinne des § 14 BGB sind, besteht das Widerrufsrecht nicht. Für Verbraucher gilt:</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>Widerrufsbelehrung</strong></p>
                    <p><strong>Widerrufsrecht</strong></p>
                    <p>Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen.</p>
                    <p>Die Widerrufsfrist beträgt vierzehn Tage ab dem Tag des Vertragsschlusses.</p>
                    <p>Um Ihr Widerrufsrecht auszuüben, müssen Sie uns (MiniFinds eGbR, Schwarzbuchenweg 49, 22391 Hamburg, 015115292977, <a href="mailto:info@minifinds.de">info@minifinds.de</a>) mittels einer eindeutigen Erklärung (z. B. ein mit der Post versandter Brief, Telefax oder E-Mail) über Ihren Entschluss, diesen Vertrag zu widerrufen, informieren. Sie können dafür das beigefügte Muster-Widerrufsformular verwenden, das jedoch nicht vorgeschrieben ist.</p>
                    <p>Zur Wahrung der Widerrufsfrist reicht es aus, dass Sie die Mitteilung über die Ausübung des Widerrufsrechts vor Ablauf der Widerrufsfrist absenden.</p>
                    <p><strong>Folgen des Widerrufs</strong></p>
                    <p>Wenn Sie diesen Vertrag widerrufen, haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben, einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass Sie eine andere Art der Lieferung als die von uns angebotene, günstigste Standardlieferung gewählt haben), unverzüglich und spätestens binnen vierzehn Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über Ihren Widerruf dieses Vertrags bei uns eingegangen ist. Für diese Rückzahlung verwenden wir dasselbe Zahlungsmittel, das Sie bei der ursprünglichen Transaktion eingesetzt haben, es sei denn, mit Ihnen wurde ausdrücklich etwas anderes vereinbart; in keinem Fall werden Ihnen wegen dieser Rückzahlung Entgelte berechnet.</p>
                    <p>Haben Sie verlangt, dass die Dienstleistungen während der Widerrufsfrist beginnen soll, so haben Sie uns einen angemessenen Betrag zu zahlen, der dem Anteil der bis zu dem Zeitpunkt, zu dem Sie uns von der Ausübung des Widerrufsrechts hinsichtlich dieses Vertrags unterrichten, bereits erbrachten Dienstleistungen im Vergleich zum Gesamtumfang der im Vertrag vorgesehenen Dienstleistungen entspricht.</p>
                    <p><strong>Muster-Widerrufsformular</strong></p>
                    <p>(Wenn Sie den Vertrag widerrufen wollen, dann füllen Sie bitte dieses Formular aus und senden Sie es zurück.)</p>
                    <p>— An MiniFinds eGbR, Schwarzbuchenweg 49, 22391 Hamburg, 015115292977, <a href="mailto:info@minifinds.de">info@minifinds.de</a>:</p>
                    <p>— Hiermit widerrufe(n) ich/wir (*) den von mir/uns (*) abgeschlossenen Vertrag über die Erbringung der folgenden Dienstleistung (*)</p>
                    <p>— Bestellt am (*) / erhalten am (*)</p>
                    <p>— Name des/der Verbraucher(s)</p>
                    <p>— Anschrift des/der Verbraucher(s)</p>
                    <p>— Unterschrift des/der Verbraucher(s) (nur bei Mitteilung auf Papier)</p>
                    <p>— Datum _______________ (*)</p>
                    <p>-&nbsp;&nbsp;&nbsp; ENDE DIESES MUSTERWIDERRUFSFORMULARS -</p>
                    <p>(2) Das Widerrufsrecht besteht nicht, erlischt oder kann ausgeschlossen werden, wenn ein gesetzlich geregelter Fall, eine entsprechende gerichtliche Entscheidung oder ein sonstiger rechtlicher Grund besteht. Gesetzlich geregelte Fälle ergeben sich insbesondere aus §§ 312 g oder 356 BGB.</p>
                    <p>(3) Das Widerrufsrecht erlischt bei einem Vertrag über die Lieferung von nicht auf einem körperlichen Datenträger befindlichen digitalen Inhalten auch dann, wenn der Unternehmer mit der Ausführung des Vertrags begonnen hat, nachdem der Verbraucher</p>
                    <p>1. ausdrücklich zugestimmt hat, dass der Unternehmer mit der Ausführung des Vertrags vor Ablauf der Widerrufsfrist beginnt, und</p>
                    <p>2. seine Kenntnis davon bestätigt hat, dass er durch seine Zustimmung mit Beginn der Ausführung des Vertrags sein Widerrufsrecht verliert.</p>
                    <p>(4) Das Widerrufsrecht erlischt insbesondere bei einem Vertrag zur Erbringung von Dienstleistungen auch dann, wenn der Unternehmer die Dienstleistung vollständig erbracht hat und mit der Ausführung der Dienstleistung erst begonnen hat, nachdem der Verbraucher dazu seine ausdrückliche Zustimmung gegeben hat und gleichzeitig seine Kenntnis davon bestätigt hat, dass er sein Widerrufsrecht bei vollständiger Vertragserfüllung durch den Unternehmer verliert. Bei einem außerhalb von Geschäftsräumen geschlossenen Vertrag muss die Zustimmung des Verbrauchers auf einem dauerhaften Datenträger übermittelt werden. Bei einem Vertrag über die Erbringung von Finanzdienstleistungen erlischt das Widerrufsrecht abweichend von Satz 1, wenn der Vertrag von beiden Seiten auf ausdrücklichen Wunsch des Verbrauchers vollständig erfüllt ist, bevor der Verbraucher sein Widerrufsrecht ausübt.</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 10 Mitwirkungspflicht</strong></li>
                    </ul>
                    <p>(1) Sie werden uns bei der Erbringung unserer vertragsgemäßen Leistungen durch angemessene Mitwirkungshandlungen fördern. Sie werden uns beispielsweise die erforderlichen Informationen, Daten, Umstände, Verhältnisse unverzüglich mitteilen; Unterlagen, Materialien, Sachen oder Zugänge zur Erfüllung der Leistung zur Verfügung stellen; uns unverzüglich Weisungen und Freigaben erteilen und uns einen kompetenten Ansprechpartner benennen, der nicht ausgewechselt wird. Sie müssen zu Ihren Handlungen – insbesondere zu Überlassungen oder Zugangsgewährungen - berechtigt sein, insbesondere dürfen keine Rechte Dritter oder behördliche Bestimmungen verletzt werden.</p>
                    <p>(2) Soweit Sie zur Mitteilung, Bereitstellung oder zur Verfügungsüberlassung nach Abs. 1 nicht berechtigt sind, beispielsweise weil wettbewerbs-, datenschutz-, marken- und kennzeichenrechtliche Verstöße oder jegliche Verstöße gegen Rechte Dritter oder behördliche Bestimmungen vorliegen, liegt ebenso fehlende Mitwirkung vor. Sie versichern Ihre Berechtigung zu den entsprechenden Handlungen. Eine entsprechende Überprüfung durch uns wird nicht erfolgen. Von etwaigen Ansprüchen Dritter, die wegen Ihrer fehlenden Berechtigung gegen uns vorgehen, werden Sie uns auf erstes Anfordern freistellen und uns jeglichen Schaden, der wegen der Inanspruchnahme durch den Dritten entsteht, einschließlich etwaiger für die Rechtsverteidigung anfallenden Gerichts- und Anwaltskosten, ersetzen. Im Übrigen gelten die gesetzlichen Bestimmungen.</p>
                    <p>(3) Fehlende, unvollständige, schadensverursachende oder rechtsverletzende Mitwirkung – beispielsweise durch Mitteilung bzw. Zuleitung unvollständiger, unrichtiger oder nicht zur rechtmäßigen Verwendung geeigneter Informationen, Daten, Stoffe oder Unterlagen – berechtigt uns zur Beendung des Vertrags, im Falle eines Vertrages mit einem Unternehmer auch ohne Auswirkung auf die vereinbarte Vergütung.</p>
                    <p>(4) Entsteht uns durch fehlerhafte Mitwirkung ein Schaden, besteht ein Schadensersatzanspruch. Sie stellen uns in diesem Fall ebenso von sämtlichen Ansprüchen Dritter frei, die Dritte im Zusammenhang mit von Ihnen zumindest grob fahrlässig fehlerhaft durchgeführten Mitwirkungshandlungen geltend machen.</p>
                    <ul>
                    <li><strong> 11</strong> <strong>Kommunikation</strong></li>
                    </ul>
                    <p>(1) Zur Gewährleistung einer schnellen und einfachen Kommunikation untereinander erfolgt die Kommunikation grundsätzlich über E-Mail oder Telefon. Sie willigen dazu ein, dass Ihnen Informationen per E-Mail, soweit vorhanden Ihrem Konto auf unseren Plattformen, postalisch oder auf anderem Weg zugesandt werden.</p>
                    <p>(2) Der Versand und die Kommunikation erfolgen auf Ihr Risiko. Für Störungen in den Leitungsnetzen des Internets, für Server- und Softwareprobleme Dritter oder Probleme eines Post- oder Zustellungsdienstleisters sind wir nicht verantwortlich und haften nicht.</p>
                    <ul>
                    <li><strong> 12 Technische Verfügbarkeit, Daten, Funktionalität und Inhalte</strong></li>
                    </ul>
                    <p>(1) Die Plattformen sind 24 Stunden am Tag, 7 Tage die Woche zugänglich, außer im Fall höherer Gewalt oder einem außerhalb unseres Einflusses liegenden Ereignis und vorbehaltlich von Ausfällen und Wartungsarbeiten, die für den Betrieb erforderlich sind. Wir wirken mit großer Sorgfalt auf eine höchstmögliche Erreichbarkeit hin. Die Verfügbarkeit hängt unter anderem von Ihrer technischen Ausstattung ab. Verfügbarkeitsunterbrechungen können durch notwendige Wartungs- und Sicherheitsarbeiten oder unvorhergesehen Ereignissen eintreten, die nicht in unserem Einflussbereich liegen.</p>
                    <p>(2) Wir haften nicht für Ihren Verlust von Daten oder von daraus resultierenden Schäden, soweit die Schäden durch eine regelmäßige und vollständige Sicherung der Daten bei Ihnen nicht eingetreten wären.</p>
                    <p>(3) Wir können jegliche Funktionsweise, das Aussehen, den Aufbau oder die Inhalte unserer Plattformen verändern, ohne Ihre Zustimmung einzuholen.</p>
                    <p>(4) Wir sind berechtigt, alle Inhalte – auch User-Generated-Content - zu sperren oder zu verändern.</p>
                    <ul>
                    <li><strong> 13 Rechteeinräumung an Daten</strong></li>
                    </ul>
                    <p>(1) Sie verpflichten sich, über die Plattformen keine Texte, Bilder, Video, Audiodateien und/oder sonstige Inhalte („Dateien“) zu verbreiten, die gegen geltendes Recht, gegen die guten Sitten und/oder gegen diese AGB verstoßen. Sie verpflichten sich insbesondere, die Rechte Dritter, wie Urheberrechte, Markenrechte, Patent- und Gebrauchsmusterrechte, Designrechte, Datenbankrechte sowie jegliche sonstigen gewerblichen Schutzrechte (nachstehend „Schutzrechte“), zu beachten.</p>
                    <p>(2) Sie räumen uns hiermit ein umfassendes, ausschließliches, räumlich und zeitlich unbegrenztes und für alle Nutzungsarten uneingeschränkt geltendes Nutzungsrecht an den Dateien bzw. Schutzrechten ein, die Sie über unsere Plattformen veröffentlichen oder auf unsere Plattform oder in das Nutzerkonto hochladen oder uns auf jede andere Weise zuleiten, insbesondere das Nutzungsrecht an Ihrem Bild, Ihrem Namen bzw. Unternehmensnamen, Ihrer Marke und jeglichen anderen Materialien. Soweit dies nach anwendbarem Recht möglich ist, verzichten Sie hiermit unbedingt und unwiderruflich auf alle Urheberpersönlichkeitsrechte, die an den Dateien bestehen, inklusive des Namensnennungsrechts und des Entstellungsverbots.</p>
                    <p>(3) Die Rechteeinräumung umfasst insbesondere das Recht, die Dateien für eigene oder fremde Zwecke in jeder Weise weltweit und zeitlich unbefristet zu verwerten, einschließlich der Verwertung in und auf Produkten, ob eigene oder solche für Dritte, in allen Verwendungsarten. Sie umfasst außerdem das Recht, die Dateien zu vervielfältigen und/oder zu veröffentlichen. Zu den Rechten gehört auch das Bearbeitungsrecht, dh die Berechtigung, die Dateien weiter zu bearbeiten oder durch Dritte weiter zu bearbeiten lassen.</p>
                    <p>(4) Soweit wir Dateien für Sie erstellen, verbleiben sämtliche Urheber- und Nutzerrechte bei uns.</p>
                    <ul>
                    <li><strong> 14 Unsere Rechte an unseren Plattformen</strong></li>
                    </ul>
                    <p>(1) Sie erklären sich einverstanden, dass es sich bei den Plattformen und allen mit ihnen zusammenhängenden Anwendungen um Datenbankwerke und um Datenbanken i. S. v. §§ 4 Abs. 2, 87a Abs. 1 UrhG handelt, deren rechtliche Inhaber wir sind. Alle zugehörigen Anwendungen unterfallen dem Schutz nach §§ 69a ff. UrhG. Sie sind urheberrechtlich geschützt.</p>
                    <p>(2) Die Rechte an allen sonstigen Elementen unserer Plattformen, insbesondere die Nutzungs- und Leistungsschutzrechte an den von uns eingestellten oder per Rechteeinräumung erworbenen Inhalten und Dokumenten, stehen ebenfalls ausschließlich uns zu. Insbesondere Marken, sonstige Kennzeichen, Firmenlogos, Schutzvermerke, Urhebervermerke oder andere der Identifikation unserer Plattformen dienender einzelner Elemente davon dienende Merkmale dürfen nicht entfernt oder verändert werden. Das gilt ebenso für Ausdrucke.</p>
                    <ul>
                    <li><strong> 15 Änderung der Dienste</strong></li>
                    </ul>
                    <p>Wir behalten uns vor, den zur Inanspruchnahme unserer Leistungen erforderlichen Zugriff auf Software, Online-Datenbanken, Funktionen, Betriebssysteme, Dokumentationen und alle anderen Bestandteile unserer Software sowie ihre Funktionsweise– soweit rechtlich zulässig auch ohne vorherige Ankündigung – insgesamt oder in Teilen, jederzeit, vorübergehend oder auf Dauer, einzustellen, zu verändern, oder einzuschränken. Insbesondere behalten wir uns vor, Eigenschaften unserer Leistungen (beispielsweise Design, Layout, Rubriken, Struktur oder Verfügbarkeit) zu verändern, zu deaktivieren, kostenfreie Bestandteile in kostenpflichtige umzustellen, bestimmte Funktionen nicht weiter zu unterstützen oder die Komptabilität (beispielsweise zu bestimmten Gerätetypen oder Betriebssystemen) auszusetzen.</p>
                    <ul>
                    <li><strong> 16 Endbenutzer-Lizenzvertrag (EULA)</strong></li>
                    </ul>
                    <p>(1) Wir gewähren Ihnen ein persönliches, nicht exklusives, widerrufliches, nicht übertragbares und weltweites Nutzungsrecht an den Plattformen - insbesondere jeglichen Softwarefunktionen auf der Webseite oder Apps -, ihren Inhalten, Diensten, sonstigen Funktionen und allen Updates. Dieses wird ausschließlich für Ihren eigenen Bedarf und im Rahmen der Nutzung der Plattformen und deren Diensten und unter Ausschluss jeglicher anderen Zwecke gewährt.</p>
                    <p>(2) Unsere digitalen Produkte (insbesondere Apps, Software) werden an Sie lizenziert und nicht an Sie verkauft.</p>
                    <p>(3) Die Lizenz gibt ihnen kein Nutzungsrecht am Inhalt. Es ist insbesondere verboten:</p>
                    <ul>
                    <li>Die Plattformen, ihre Inhalte, Dienste, sonstige Funktionen oder Updates anzupassen, zu verändern, zu übersetzen, zu bearbeiten, eine Rückumstellung vorzunehmen, zu zerlegen, zu transkodieren oder durch Reverse Engineering die Plattform oder einen Teil davon abzubilden;</li>
                    <li>Die Plattformen, ihre Inhalte, Dienste, sonstige Funktionen oder Updates zu exportieren, oder ganz oder teilweise mit anderen Softwareprogrammen zu verbinden, oder sie ganz oder teilweise, mit jeglichem Mittel und in jeglicher Form dauerhaft oder vorläufig zu reproduzieren;</li>
                    <li>Inhalte der Datenbanken, die aus den Plattformen entstanden sind, zu extrahieren oder weiterzuverwenden;</li>
                    <li>Werke zu erstellen, die von der lizenzierten Plattform abgeleitet sind;</li>
                    <li>Prozesse oder Software zu nutzen, die dazu bestimmt sind, die Plattformen, ihre Inhalte, Dienste, sonstige Funktionen oder Updates ohne unsere Zustimmung zu kopieren;</li>
                    <li>Systeme einzurichten, die imstande sind, die Plattformen zu hacken.</li>
                    <li>Dritten unsere Leistungen ohne unsere Zustimmung anzubieten oder zu überlassen.</li>
                    </ul>
                    <p>(4) Bei einer Verletzung des Verbots bestehen Strafbarkeit und Schadensersatzpflicht.</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 17 Werbung Dritter</strong></li>
                    </ul>
                    <p>(1) Wir behalten uns das Recht vor, Ihnen auf unseren Plattformen Werbung Dritter anzuzeigen. Wir haben keinen Einfluss auf die Werbung, insbesondere nicht auf ihren Inhalt, ihre Zuverlässigkeit oder ihre Genauigkeit. Die Anzeige von Werbung erfolgt ohne unsere Prüfung, insbesondere wird sie von uns inhaltlich nicht gebilligt – verantwortlich ist alleine Werbetreibende. Bei jeder Form der Beanspruchung – insbesondere durch Klicken, Nutzung ihrer mittels application programming interface („API“) durchgeführten Leistungen oder dem Besuch ihrer auf der Werbung verlinkten Plattformen – gelten ihre Vertragsbedingungen, AGB und Datenschutzbestimmungen.</p>
                    <p>(2) Werbung kann insbesondere mit der Verlinkung von Plattformen Dritter oder API-Anwendungen Dritter einhergehen. Auch hierbei besteht alleine die Verantwortlichkeit des jeweiligen Anbieters der Werbung. Es gelten dessen Vertragsbedingungen, AGB und Datenschutzbestimmungen.</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 18 Urheber- und sonstige Rechte</strong></li>
                    </ul>
                    <p>Wir haben an allen Bildern, Filmen, Texten und sonstigen vom Urheberrecht oder ähnlichen Rechten, insbesondere durch geistige Eigentumsrechte, geschützten Inhalten, die auf unserer Webseite, unseren Profilen auf anderen Webseiten, unseren Social-Media-Profilen und allen unseren Plattformen veröffentlicht werden, Urheberrechte oder sonstige Rechte. Eine Verwendung der Bilder, Filme, Texte und sonstiger Rechte ist ohne unsere schriftliche Zustimmung nicht gestattet.</p>
                    <ul>
                    <li><strong> 19 Datenschutz und Datensicherheit</strong></li>
                    </ul>
                    <p>(1) Wir erheben personenbezogene Daten von Ihnen sowie ggf. andere, von Ihnen zugeleitete oder im Zuge der Vertragserfüllung von uns erlangte Daten zum Zweck der Vertragsdurchführung sowie zur Erfüllung der vertraglichen und vorvertraglichen Pflichten. Die Datenerhebung und Datenverarbeitung ist zur Vertragserfüllung erforderlich und beruht auf Artikel 6 Abs.1 b) DSGVO. Wir verarbeiten sie nach den Verpflichtungen der DSGVO. Nach § 5 Abs. 1 DSGVO müssen personenbezogene Daten im Wesentlichen:</p>
                    <p>(a) auf rechtmäßige und faire Weise und in einer für die betroffene Person nachvollziehbaren Weise verarbeitet werden („Rechtmäßigkeit, Verarbeitung nach Treu und Glauben, Transparenz“);</p>
                    <p>(b) für festgelegte, eindeutige und legitime Zwecke erhoben werden und dürfen nicht in einer mit diesen Zwecken nicht zu vereinbarenden Weise weiterverarbeitet werden („Zweckbindung“);</p>
                    <p>(c) dem Zweck angemessen und erheblich sowie auf das für die Zwecke der Verarbeitung notwendige Maß beschränkt sein („Datenminimierung“);</p>
                    <p>(d) sachlich richtig und erforderlichenfalls auf dem neuesten Stand sein; es sind alle angemessenen Maßnahmen zu treffen, damit personenbezogene Daten, die im Hinblick auf die Zwecke ihrer Verarbeitung unrichtig sind, unverzüglich gelöscht oder berichtigt werden („Richtigkeit“);</p>
                    <p>(e) in einer Form gespeichert werden, die die Identifizierung der betroffenen Personen nur so lange ermöglicht, wie es für die Zwecke, für die sie verarbeitet werden, erforderlich ist („Speicherbegrenzung“);</p>
                    <p>(f) in einer Weise verarbeitet werden, die eine angemessene Sicherheit der personenbezogenen Daten gewährleistet, einschließlich Schutz vor unbefugter oder unrechtmäßiger Verarbeitung und vor unbeabsichtigtem Verlust, unbeabsichtigter Zerstörung oder unbeabsichtigter Schädigung durch geeignete technische und organisatorische Maßnahmen („Integrität und Vertraulichkeit“).</p>
                    <p>(2) Daten werden grundsätzlich nicht an Dritte übermittelt, wenn keine entsprechende Pflicht besteht oder die Vertragsdurchführung oder der Einhaltung einer gesetzlichen Frist eine Datenübermittlung erforderlich macht, beispielsweise wenn die Weitergabe der Daten erforderlich sind, um für Sie eine zur Vertragsdurchführung notwendige Abfrage durch einen Drittanbieter durchzuführen, Ihre Daten an einen Zahlungsanbieter weitergeleitet werden oder Subunternehmer in Anspruch genommen werden, um zur Erfüllung einer Leistungspflicht Ihnen gegenüber beizutragen. In diesen Fällen werden die Dienstleister vielfach mit Ihnen ein Vertragsverhältnis haben, so dass sie auf eigene Verantwortung handeln.</p>
                    <p>(3) Sobald Daten für den Zweck ihrer Verarbeitung nicht mehr erforderlich sind und falls eine gesetzliche Aufbewahrungspflicht nicht weiter besteht, werden sie von uns gelöscht. In Anbahnung unseres Vertragsverhältnisses sowie bei dessen Durchführung bewahren wir Ihre Daten auf. Dabei kann es auch notwendig sein, dass nach Kündigung unseres Vertragsverhältnisses Daten weiter aufbewahrt werden. Beispielsweise müssen Rechnungsdaten (Abrechnungsunterlagen) gemäß § 147 Abgabenordnung 10 Jahre aufbewahrt werden. Solange ein für uns ausführender Dienstleister ebenso einen Vertrag über die Durchführung Ihrer Leistung mit uns hat, bleiben wir verpflichtet, die Daten entsprechend der vereinbarten Aufbewahrungsfristen vorzuhalten.</p>
                    <p>(4) Sie haben das Recht auf Auskunft, Datenübertragung, Löschung, Berichtigung, Einschränkung oder Sperrung Ihrer personenbezogenen Daten. Insbesondere haben Sie einen Anspruch auf eine unentgeltliche Auskunft über alle personenbezogenen Daten.</p>
                    <p>Ihre Anfrage kann an uns gestellt werden. Außerdem stehen Ihnen entsprechende verwaltungsrechtliche oder gerichtliche Rechtsbehelfe oder die bei einer Aufsichtsbehörde offen.</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 20 Haftung, Freistellung und Aufwendungsersatz</strong></li>
                    </ul>
                    <p>(1) Wir haften gegenüber Ihnen in allen Fällen vertraglicher und außervertraglicher Haftung bei Vorsatz und grober Fahrlässigkeit nach Maßgabe der gesetzlichen Bestimmungen auf Schadensersatz oder Ersatz vergeblicher Aufwendungen.</p>
                    <p>(2) In sonstigen Fällen haften wir – soweit in Abs. 3 nicht abweichend geregelt – nur bei Verletzung einer Vertragspflicht, deren Erfüllung die ordnungsgemäße Durchführung des Vertrags überhaupt erst ermöglicht und auf deren Einhaltung Sie als Vertragspartner regelmäßig vertrauen dürfen (so genannte Kardinalpflicht), und zwar beschränkt auf den Ersatz des vorhersehbaren und typischen Schadens. In allen übrigen Fällen ist unsere Haftung vorbehaltlich der Regelung in Abs. 3 ausgeschlossen.</p>
                    <p>(3) Unsere Haftung für Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit und nach dem Produkthaftungsgesetz bleibt von den vorstehenden sowie allen übrigen in diesen AGB sowie zwischen uns getroffenen Haftungs-, Gewährleistungs- oder Verantwortungsbeschränkungen und Haftungs-, Gewährleistungs- oder Verantwortungsausschlüssen unberührt.</p>
                    <p>(4) Sie stellen uns von etwaigen Ansprüchen Dritter, die wegen möglicher schuldhafter Verletzungen des Partners gegen seine Pflichten – insbesondere aus diesen AGB – gegen uns und/oder unseren Erfüllungsgehilfen geltend gemacht werden, auf erstes Anfordern frei. Sie ersetzen uns jeglichen Schaden, der wegen der Inanspruchnahme durch den Dritten entsteht, einschließlich etwaiger für die Rechtsverteidigung anfallenden Gerichts- und Anwaltskosten. Im Übrigen gelten die gesetzlichen Bestimmungen.</p>
                    <p>(5) Wir haben Anspruch auf Ersatz der Aufwendungen, die wir den Umständen nach für erforderlich halten durften und nicht zu vertreten hatten, insbesondere jegliche Aufwendungen zum Schutz des Vertragsgutes sowie daneben auf eine ortsübliche, angemessene Vergütung.&nbsp;</p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 21 Leistungsort, Anwendbares Recht, Vertragssprache und Gerichtsstand</strong></li>
                    </ul>
                    <p>(1) Für alle Leistungen aus dem Vertrag wird als Erfüllungsort Hamburg vereinbart.</p>
                    <p>(2) Es gilt das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts. Sind sowohl Sie als auch wir zum Zeitpunkt des Vertragsschlusses Kaufleute und haben Sie Ihren Sitz zum Zeitpunkt des Vertragsschlusses in Deutschland, ist ausschließlicher Gerichtsstand unser Sitz in Hamburg. Im Übrigen gelten für die örtliche und die internationale Zuständigkeit die anwendbaren gesetzlichen Bestimmungen.</p>
                    <p>(3) Vertragssprache ist, soweit nichts Anderes schriftlich vereinbart ist, Deutsch. Jegliche übersetzten Rechtstexte oder Dokumente dienen alleine einem besseren Verständnis. Insbesondere in Bezug auf eine Vertragsabrede als auch auf diese AGB, die Datenschutzbestimmungen oder alle anderen Rechtstexte oder Dokumente sind die deutschen Versionen rechtsverbindlich; dies gilt insbesondere bei Abweichungen oder Auslegungsunterschieden zwischen solchen Rechtstexten oder Dokumenten.</p>
                    <p>(4) In Bezug auf Streitigkeiten mit Verbrauchern hat die&nbsp; &nbsp;EU-Kommission eine Internetplattform zur Online-Streitbeilegung geschaffen – die alternative Streitbeilegung nach der ODR-Verordnung und § 36 VSBG. Diese Plattform dient als Anlaufstelle zur außergerichtlichen Beilegung von Streitigkeiten betreffend vertragliche Verpflichtungen, die aus Online-Kaufverträgen erwachsen. Nähere Informationen sind unter dem folgenden Link verfügbar: <a href="https://ec.europa.eu/consumers/odr"></a><a href="http://ec.europa.eu/consumers/odr">http://ec.europa.eu/consumers/odr</a></p>
                    <p>Die Teilnahme an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle ist nicht verpflichtend und wird von uns nicht wahrgenommen.</p>
                    <p><strong>&nbsp;</strong></p>
                    <ul>
                    <li><strong> 22 Schlussbestimmungen</strong></li>
                    </ul>
                    <p>(1) Änderungen und Ergänzungen dieser AGB erfolgen schriftlich, das Recht hierzu behalten wir uns vor. Änderungen setzen voraus, dass Sie nicht unangemessen benachteiligt werden, kein Verstoß gegen Treu und Glauben geschieht und der Änderung nicht widersprochen wird. Im Fall einer Änderung erfolgt eine Mitteilung über einen der Kommunikationskanäle – insbesondere per E-Mail – 2 Monate vor ihrer Wirksamkeit. Die Änderung wird wirksam, wenn ihr nicht innerhalb dieser Frist widersprochen wird – hiernach werden die geänderten AGB gültig.</p>
                    <p>(2) Eine Abtretung dieses Vertrags an ein anderes Unternehmen wird vorbehalten. Sie wird 1 Monat nach Absendung einer Abtretungsmitteilung über einen unserer Kommunikationskanäle – insbesondere per E-Mail – an Sie gültig. Sie haben im Fall einer Abtretung ein Kündigungsrecht, welches 1 Monat nach Zugang der Mitteilung der Abtretung gilt. Alle uns eingeräumten Rechte gelten zugleich als unseren Rechtsnachfolgern eingeräumt.</p>
                    <p>(3) Im Falle der Unwirksamkeit einzelner Bestimmungen dieser AGB, wird die Rechtswirksamkeit der übrigen Bestimmungen nicht berührt. Die unwirksame Bestimmung wird durch eine wirksame Bestimmung ersetzt, die dem beabsichtigten wirtschaftlichen Zweck am nächsten kommt.</p>
                </div>
            </div>
            <div  x-show="selectedTab === 'sellerAgb'" x-collapse  x-cloak>
                <div class="w-full  py-10">
                <p><strong>Allgemeine Geschäftsbedingungen für Anbieter von Leistungen auf MiniFinds</strong></p>
                <p>Unsere AGB gelten für Anbieter von Leistungen von MiniFinds auf dem Gebiet der Kindersecondhand Produkte auf unseren Webseiten oder Apps, auf Profilen, auf fremden Webseiten oder Apps, sowie auf allen anderen Vertriebswegen („Plattformen“) als vereinbart. Insbesondere finden Sie als unser Vertragspartner und Anbieter einer Leistung auf unseren Plattformen Ihre Rechte und Pflichten als Anbieter gegenüber uns sowie die wichtigsten Datenschutzbestimmungen.</p>
                <p><strong>Präambel MiniFinds</strong></p>
                <p><strong>&nbsp;</strong></p>
                <p>Minifinds ist der Second-Hand-Shop für Kinderkleidung, -zubehör und Spielzeugen:</p>
                <ul>
                <li>Bequem, stressfrei und</li>
                <li>Nachhaltig - gib gut erhaltener Kinderkleidung und Spielzeugen eine neue Chance.</li>
                </ul>
                <ul>
                <li><strong> 1 Geltungsbereich</strong></li>
                </ul>
                <p>(1) Diese Allgemeinen Geschäftsbedingungen (im Folgenden: „AGB“) gelten für alle</p>
                <p>Vertragsverhältnisse zur Regelung der Bereitstellung von Angeboten an Nutzer unserer Plattformen</p>
                <p>zwischen</p>
                <p>MiniFinds eGbR</p>
                <p>Schwarzbuchenweg 49</p>
                <p>22391 Hamburg,</p>
                <p>(mehr Informationen im Impressum: <a href="https://www.minifinds.de/imprint"></a><a href="https://www.minifinds.de/imprint">https://www.minifinds.de/imprint</a>)</p>
                <p>(nachfolgend „Verwender“, „wir“, oder „uns“ genannt)</p>
                <p>und Ihnen</p>
                <p>(nachfolgend „Anbieter“, „Partner“, „Ihr“ oder „Sie“ genannt).</p>
                <p>(2) Die AGB gelten unabhängig davon, ob Sie Verbraucher oder Unternehmer sind.</p>
                <p>(3) “Verbraucher” sind Sie, wenn Sie eine natürliche Person sind, die einen Vertrag mit uns zu Zwecken abschließt, der überwiegend weder Ihrer gewerblichen noch Ihrer selbständigen, freiberuflichen, öffentlich-rechtlichen oder gemeinnützigen beruflichen Tätigkeit zugerechnet werden kann.</p>
                <p>(4) “Unternehmer”, sind Sie, wenn Sie eine natürliche Person sind oder einen Vertragsschluss für eine juristische Person oder eine rechtsfähige Personengesellschaft tätigen, die bei Abschluss des Vertrages mit uns in Ausübung ihrer gewerblichen, freiberuflichen, selbständigen, öffentlich-rechtlichen oder gemeinnützigen beruflichen Tätigkeit handelt.</p>
                <p>(5) Diese AGB gelten auf allen unseren Plattformen. “Plattformen” sind alle unsere Vertriebs- und Operationskanäle und -dienste. Insbesondere sind es unsere Webseiten oder Apps und unsere Profile auf Webseiten oder Apps unserer Partner. Insbesondere sind es alle unsere Räumlichkeiten; alle unsere physischen oder elektronischen Unterlagen wie E-Mails, Auftragsdokumente oder Informationsmaterialien; alle unsere Vertriebsflächen sowie unsere Webseiten oder Apps und unsere Profile auf Webseiten oder Apps unserer Partner.</p>
                <p>(6) Maßgebend ist die jeweils bei Abschluss des Vertrags gültige Fassung der AGB. Abweichende AGB werden nicht akzeptiert. Dies gilt auch, wenn wir der Einbeziehung nicht ausdrücklich widersprochen haben. Etwas anderes kann gelten, soweit in diesen AGB in Einzelfällen etwas anderes bestimmt ist. Soweit in eine andere Sprache als Deutsch übersetzte Rechtstexte oder Dokumente bestehen, sind die deutschen Rechtstexte oder Dokumente rechtlich verbindlich und damit anwendbar – die übersetzten Rechtstexte oder Dokumente dienen alleine zum besseren Verständnis.</p>
                <p>(7) Alle zwischen Ihnen und uns im Zusammenhang mit einer Leistung getroffenen Vereinbarungen ergeben sich insbesondere aus einer Bestellung bzw. Beauftragung sowie den dazugehörigen Anlagen, unserer Bestätigung, unserer Annahme sowie ergänzend, soweit dort nicht geregelt, aus diesen AGB. Diese AGB finden auch auf spätere Bestellungen bzw. Beauftragungen Anwendung, die Sie während oder nach Ablauf der Vertragslaufzeit abgeben, es sei denn, zu diesem Zeitpunkt sind andere AGB einbezogen worden.</p>
                <p>(8) Mit Ausnahme schriftlicher Änderungen und Ergänzungen dieser AGB werden elektronisch oder digital erstellte Dokumente oder Unterlagen schriftlichen Dokumenten oder Unterlagen gleichgestellt.</p>
                <p>(9) Diese AGB gelten auch für andere, zwischen Ihnen und uns geschlossenen Verträge, soweit keine speziellen, auf die andere Vertragsart bezogenen AGB vorliegen und Klauseln dieser AGB inhaltlich Anwendung finden können.</p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 2 Anmeldung, Konto, Auswahlverfahren und Vertragsschluss</strong></li>
                </ul>
                <p>(1) Für die Nutzung unserer Plattformen benötigen Sie ein Konto. Insbesondere wird es für die Inanspruchnahme unserer Plattformen zur Vermittlung und Anbahnung von Abschluss von Verträgen mit Nutzern vorausgesetzt. Um es zu erhalten, ist eine Anmeldung erforderlich. Um einige Funktionen unserer Plattformen nutzen zu können, kann eine Anmeldung und Anlage eines Kontos erforderlich sein. Insbesondere wird es für Inanspruchnahme unserer Plattformen zur Vermittlung, Anbahnung von Abschluss von Verträgen mit Nutzern vorausgesetzt.</p>
                <p>(2) Die Angabe der bei Anmeldung abgefragten Daten ist verpflichtend. Sie versichern ihre Vollständigkeit und Richtigkeit.</p>
                <p>(3) Für die Anmeldung bestehen folgende Voraussetzungen:</p>
                <ul>
                <li>Verbraucher sind unbeschränkt geschäftsfähige natürliche Personen im Alter von über 18 Jahren oder beschränkt Geschäftsfähige, die mit Zustimmung Ihrer Eltern handeln</li>
                <li>Unternehmen als natürliche Personen erfüllen die Voraussetzungen eines Verbrauchers</li>
                <li>Unternehmer als Personen- oder Kapitalgesellschaften sowie jegliche anderen Körperschaften, Vereinigungen oder Gemeinschaften sind rechtsfähig und haben einen vertretungsberechtigten Vertreter. Die Angabe eines Postfachs genügt nicht.</li>
                </ul>
                <p>(4) Ein Anspruch auf Anmeldung besteht nicht. Wir sind berechtigt, eine Anmeldung abzulehnen. Mit dem Abschluss der Anmeldung entsteht und beginnt zwischen Ihnen und uns ein Vertragsverhältnis, der “Anbietervertrag”. Sie stimmen mit der Anmeldung zudem diesen AGB und der Datenverarbeitung gemäß unserer Datenschutzerklärung zu.</p>
                <p>(5) Vor der Erstellung eines Kontos behalten wir uns die Durchführung eines Auswahlverfahrens vor. Dieses richtet sich an unseren Auswahlkriterien als wesentlicher Bestandteil unseres Qualitätsmanagements. Wir sind berechtigt, jede Auswahl ohne Angabe zu gründen zu treffen, insbesondere eine Ablehnungsentscheidung. Ein Anspruch auf eine schnelle oder positive Auswahl, insbesondere auf die Gewährung einer Anmeldung, besteht nicht. Sollten dabei Kosten entstehen, werden diese von uns nicht erstattet.</p>
                <p>(6) Mit der Anmeldung erhalten Sie ein Konto, welches alle notwendigen Daten für die Nutzung enthält. Die Nutzung darf nur durch Sie selbst erfolgen, insbesondere dürfen Sie Dritten die Nutzung des Kontos nicht gestatten oder das Konto nicht an Dritte übertragen (Accountsharing). Die Zugangsdaten der Plattformen setzen ein Passwort voraus, welches besonders sicher gewählt werden muss. Die Zugangsdaten dürfen nicht an Dritte – mit Ausnahme von zu Verschwiegenheit verpflichteten Mitarbeitern oder Gesellschaftern, die sich mit den AGB und Datenschutzbestimmungen einverstanden erklärt haben – weitergegeben werden, insbesondere um den Zugang unbefugter Personen zu vertraulichen Informationen von Nutzern auszuschließen. Das Passwort kann jederzeit geändert werden. Mehrere Konten einer Person sind unzulässig. Jegliche gemeinschaftliche Nutzung ist nicht gestattet (Accountsharing). Zugangsdaten sind sicher aufzubewahren und dem Verwender ist sofort Mitteilung zu machen, sobald der Eindruck einer Fremdnutzung entsteht. Sollten wir den Verdacht einer Fremdnutzung haben, sind wir berechtigt, alles Erforderliche zu unternehmen, einschließlich der Einsicht, Sperrung oder Löschung des Anbieterkontos. Der Anbieter haftet für die durch Fremdnutzung entstehenden Schäden und Kosten, die uns für jegliche Maßnahmen entstehen. Das Konto besteht bis zum Eintritt der Wirkung der Kündigung.</p>
                <p>(7) Der Anbieter kann sich jederzeit in sein Konto einloggen, seine Profildaten einsehen, verändern, ergänzen, mit uns, den Anbietern oder ggf. allen sonstigen Beteiligten kommunizieren oder jedwede sonstigen Handlungen vornehmen, die den Anbietervertrag betreffen.</p>
                <p>(8) Sie sind für den Inhalt und die Qualität aller Angaben auf den Plattformen verantwortlich. Sie versichern, dass Ihre Angaben richtig und vollständig sind. Die Informationen, Nachweise und sonstige Daten müssen laufend auf aktuellem Stand gehalten werden. Sie verpflichten sich, keine strafbaren, rechtswidrigen oder die Rechte Dritter verletzenden Inhalte und Daten einzugeben, hochzuladen oder auf jegliche Weise uns oder den Nutzern bereitzustellen sowie die Plattformen auf jegliche rechtswidrige Weise zu nutzen, beispielsweise zur Begehung von Straftaten oder zum Angebot rechtswidriger Leistungen.</p>
                <p>(9) Sie verpflichten sich, das Serviceangebot nicht missbräuchlich zu nutzen und insbesondere keine rechtswidrigen, sittenwidrigen, diffamierenden, anstößigen, obszönen, pornografischen oder politisch radikalen Inhalte zu verbreiten.</p>
                <p>(10) Ihre Daten können Nutzern der Plattformen zugänglich gemacht werden.</p>
                <p>(11) Sie dürfen den sicheren Betrieb unserer Plattformen nicht gefährden. Es ist alles zu unterlassen, was andere Benutzer der Plattformen belästigen könnte oder über die bestimmungsgemäße Benutzung unserer Plattformen hinausgeht. Sie sind insbesondere verpflichtet, folgendes zu unterlassen:</p>
                <ul>
                <li>Dateien hochzuladen oder zu versenden, die einen Virus oder sonstige Schadsoftware enthalten oder sonstige Eingriffe vorzunehmen, die die Funktionalität oder die Erreichbarkeit der Plattformen beeinträchtigen oder Inhalte verändern oder löschen könnte,</li>
                <li>Jede Form von Werbung hochzuladen oder zu versenden, besonders E-Mail-Werbung, SMS-Werbung, Kettenbriefe oder andere belästigende Inhalte,</li>
                <li>Die Plattformen einer übermäßigen Belastung auszusetzen oder auf jede andere Weise das Funktionieren zu stören oder zu gefährden,</li>
                <li>Ohne schriftliche Zustimmung Crawler, Spider, Scraper oder andere automatisierte Mechanismen zu nutzen, um auf die Plattformen zuzugreifen und Inhalte zu sammeln,</li>
                <li>Informationen wie E-Mail-Adressen oder Rufnummern anderer Benutzer ohne vorherige Einwilligung zu sammeln oder zu verwenden,</li>
                <li>Inhalte der Plattformen oder Dritter ohne vorherige Einwilligung durch uns oder die Dritten zu vervielfältigen, öffentlich zugänglich zu machen, zu verbreiten, zu bearbeiten oder in einer Art und Weise zu nutzen, die über die bestimmungsgemäße Nutzung hinausgeht.</li>
                </ul>
                <p>(12) Wird eine der Pflichten dieses § verletzt, sind wir berechtigt, in Hinblick auf Ihr Konto jede Maßnahme zu treffen. Insbesondere sind wir berechtigt, Sie ohne Angabe von Gründen zu einer Stellungnahme aufzufordern, das Konto vorläufig zu sperren, eine Verwarnung auszusprechen, oder das Konto dauerhaft zu sperren oder zu löschen. Darüber hinaus behalten wir uns ausdrücklich die Geltendmachung von zivil- und strafrechtlichen Ansprüchen vor. Die Sanktionen betreffen nicht die Zahlungspflicht bei bereits zustande gekommenen Leistungsverhältnissen, insbesondere wenn die Leistung bereits (teilweise) erbracht wurde.</p>
                <p>(13) Sollten Leistungen auf unseren Plattformen auch ohne ein Konto in Anspruch genommen werden können, geben Sie bereits mit der Nutzung unserer Plattformen ein Angebot auf Abschluss eines Vertrages für die Dauer der Nutzung einer Plattform gemäß dieser AGB ab, welches wir durch die Erbringung der Leistung annehmen.</p>
                <ul>
                <li><strong> 3 Preise, Zahlung, Verzug, Zahlungsbedingungen, Aufrechnung, Zurückbehaltungsrecht </strong></li>
                </ul>
                <p>(1) Für unsere kostenpflichtigen Leistungen auf unseren Plattformen gilt: Die von uns angegebenen Preise sind – soweit im Einzelfall nichts anderes präsentiert oder vereinbart wurde– Bruttopreise inklusive der Umsatzsteuer.</p>
                <p>(2) Unsere Vergütung wird – soweit keine andere Vereinbarung zwischen Ihnen und uns besteht – nach Abschluss des Vertrages und vor der jeweiligen Leistungserbringung fällig. Sie ist spätestens innerhalb von 2 Wochen nach Versand unserer Rechnung (Rechnungsdatum) zu bezahlen. Unterbleibt die Zahlung, tritt Zahlungsverzug ein. Bei Zahlungsverzug sind wir berechtigt, nach den gesetzlichen Bestimmungen Verzugszinsen und weiteren Schadensersatz geltend zu machen. Der Verzugszins gegenüber Verbrauchern beträgt für das Jahr 5 Prozentpunkte über dem Basiszinssatz nach § 288 BGB; gegenüber Unternehmern beträgt der Verzugszins für das Jahr 9 Prozentpunkte über dem Basiszinssatz nach § 288 BGB.</p>
                <p>(3) Wir ermöglichen Ihnen die Nutzung verschiedener Zahlungsdienste und -möglichkeiten. Sie können zur Zahlung jeden von uns bereitgestellten Zahlungsweg nutzen, insbesondere</p>
                <ul>
                <li>auf ein von uns angegebenes Konto überweisen,</li>
                <li>uns eine Einzugsermächtigung oder SEPA-Lastschriftmandat erteilen,</li>
                <li>uns per EC-/Maestro- oder Kreditkarte bezahlen,</li>
                <li>uns über eine Plattform Dritter bezahlen (beispielsweise Apple App Store, Google Play oder Amazon Appstore),</li>
                <li>oder uns über einen von uns angegebenen Zahlungsdienstleister (beispielsweise PayPal) bezahlen,</li>
                </ul>
                <p>jeweils, sofern wir eine entsprechende Zahlungsmöglichkeit anbieten. Wir behalten uns vor, Zahlungsmöglichkeiten individuell oder allgemein auszuschließen oder im Nachgang zu ergänzen.</p>
                <p>(4) Sie nehmen die Zahlungsleistung eines Zahlungsdienstleisters in Anspruch, indem Sie auf den Button des Zahlungsdienstleisters während des Bestellprozesses von Leistungen klicken. Sie werden auf die entsprechende Seite des jeweiligen Zahlungsdienstleisters geführt. Sie nehmen die Zahlungsleistung einer dritten Plattform wie Apple App Store, Google Play oder Amazon Appstore in Anspruch, indem Sie unsere App über ihn runterladen. Wir stellen hinsichtlich der Zahlung nur den Zugang zur Seite des jeweiligen Zahlungsdienstleisters oder der Plattform bereit, werden aber nicht Vertragspartei. Meistens ist es zur Nutzung von Zahlungsdiensten eines Zahlungsdienstleisters oder der Plattform erforderlich, ein Vertragsverhältnis mit dem entsprechenden Zahlungsdienstleister einzugehen. Es gelten die jeweiligen Vertragsbedingungen, AGB und Datenschutzbestimmungen.</p>
                <p>(5) Im Fall einer erteilten Einzugsermächtigung, eines SEPA-Lastschriftmandats oder der Zahlung per EC-/Maestro- oder Kreditkarte werden wir die Belastung Ihres Kontos frühestens zum Fälligkeitszeitpunkt veranlassen. Eine erteilte Einzugsermächtigung gilt bis auf Widerruf auch für weitere Aufträge.</p>
                <p>(6) Sie sind nicht berechtigt, gegenüber unseren Forderungen aufzurechnen, es sei denn, Ihre Gegenansprüche sind rechtskräftig festgestellt oder unbestritten, sowie dann, wenn Sie Mängelrügen oder Gegenansprüche aus demselben Vertragsverhältnis geltend machen.</p>
                <p>(7) Sie dürfen nur dann ein Zurückbehaltungsrecht ausüben, wenn Ihr Gegenanspruch aus demselben Vertragsverhältnis herrührt und rechtskräftig festgestellt oder unbestritten ist.</p>
                <p>(8) Für den Fall, dass auf eine unserer Forderung aus einem oder mehreren Verträgen nicht fristgerecht gezahlt wird, sind wir berechtigt, ein Inkassobüro (z.B.&nbsp;Creditreform) mit dem weiteren Einzug der fälligen Forderung zu beauftragen. Sie willigen mit Vertragsschluss ein, dass wir die zum Einzug der Forderung erforderlichen Daten und Informationen an das Inkassobüro (z.B.&nbsp;Creditreform) übermitteln und das Inkassobüro (z.B. Creditreform) zur Speicherung und Verarbeitung der Daten berechtigt ist. Insbesondere werden Name und Anschrift, Vertragsdatum, sowie Rechnungsnummer, Rechnungsbetrag und das Fälligkeitsdatum übermittelt.</p>
                <p>(9) Gebühren (jegliche Ämter, Behörden o. ä.), Honorare oder sonstige Zahlungsansprüche anderer aus der Leistungserbringung resultierender Zahlungssachverhalte&nbsp; sind nicht im Preis enthalten und werden von Ihnen gesondert und gegenüber den jeweiligen Stellen bzw. Personen entrichtet. Dies gilt auch dann, wenn diese Ausgaben durch uns vorausgelegt werden; sie sind in diesem Fall an uns zu erstatten.</p>
                <ul>
                <li><strong> 4 Unsere Leistungen und Leistungen der Anbieter</strong></li>
                </ul>
                <p>(1) Sie als Anbieter (sowie alle anderen, von uns aufgenommenen Händler) können auf unseren Plattformen ihre Leistungen – insbesondere Produkte aus dem Bereich Second-Hand für Kinderkleidung, -zubehör und Spielzeugen präsentieren und Verträge mit unseren Nutzern schließen.</p>
                <p>(2) Sie erbringen die von Ihnen auf den Plattformen präsentierten Leistungen nach einem Vertragsschluss mit einem Nutzer. Die Vertragsbeziehung zwischen Ihnen als Anbieter und einem Nutzer, wird nach Vertragsschluss durch unsere Vermittlung begründet. Die Leistungserbringung erfolgt durch Sie als Anbieter auf eigene Rechnung und Verantwortung auf der Grundlage Ihrer individuellen Beauftragung durch einen Nutzer und – soweit vorhanden - Ihren AGB. Sie haften insbesondere für etwaige Pflichtverletzungen oder Mängel aus dem Vertrag mit dem Nutzer.</p>
                <p>(3) Sie sind für alle auf die Plattform geladenen Inhalte, Anzeigen und Leistungsangebote verantwortlich. Sie verpflichten sich, keine strafbaren, rechtswidrigen, missbräuchlichen, irreführenden oder die Rechte Dritter verletzenden Leistungsangebote zu tätigen und Leistungen durchzuführen; keine entsprechenden Inhalte und Daten einzugeben, hochzuladen oder auf jegliche Weise uns oder den Nutzern bereitzustellen sowie die Plattformen auf jegliche rechtswidrige Weise zu nutzen.</p>
                <p>(4) Ihre Inhalte und Informationen müssen einen Bezug zu Ihren Leistungen haben. Werbung für nicht auf den Plattformen angebotene Leistungen ist nicht zulässig. Es ist nicht gestattet, Gütesiegel oder sonstige Symbole Dritter zu verwenden, die nicht von uns zugelassen sind.</p>
                <p>(5) Falls Sie Unternehmer sind und Angebote für Verbraucher auf den Plattformen abgeben, müssen Sie den Nutzern die gesetzlich vorgeschriebenen Verbraucherschutzinformationen erteilen. Insbesondere müssen Sie sie über das Bestehen oder Nichtbestehen des gesetzlichen Widerrufsrechts belehren.</p>
                <p>(6) Um Nutzern das Auffinden Ihrer Leistungen passend zu ihrem Bedarf zu ermöglichen, stellen wir Ihnen die Möglichkeit der Präsentation der relevanten Informationen (Bilder, Videos, Beschreibungen, Marken und Logos u.a.) oder ihren Angeboten zur Verfügung. Wir ermöglichen den Nutzern, eine Suche durchzuführen und die Suchergebnisse nach diversen Kriterien zu sortieren oder sortieren die Ergebnisse vor. Wir behalten uns vor, Informationen zu verändern, um eine bessere Verständlichkeit sicherzustellen, insbesondere bei Inhalts-, Grammatik- oder Rechtschreibfehlern.</p>
                <p>(7) Wir ermöglichen Ihnen auf folgende Weisen, Nutzern unserer Plattformen Leistungen zu präsentieren und mit ihnen Verträge zu schließen:</p>
                <ul>
                <li>Marktplatzmodell: Der Nutzer nimmt eine vom Anbieter angebotene Leistung in Anspruch.</li>
                </ul>
                <p>Je nach gewähltem Modell gilt – neben diesen AGB und soweit vorhanden – die jeweilige Vereinbarung.</p>
                <ul>
                <li><strong> 5 Marktplatzmodell</strong></li>
                </ul>
                <p>(1) Sie präsentieren Nutzern auf der Plattform ihre Leistungen. Die alleinige Präsentation stellt kein bindendes Angebot zum Abschluss eines Vertrags mit dem Nutzer dar, sondern dient der unverbindlichen Darbietung.</p>
                <p>(2) Angebote und Kostenvoranschläge des Anbieters an dem Nutzer auf den Plattformen sind freibleibend. Kostenvoranschlags- und Angebotsfehler können vor der Auftragsannahme berichtigt werden.</p>
                <p>(3) Eine rechtsverbindliche Bestellung bzw. Beauftragung kann der Nutzer ausschließlich in der Kanalstraße 14, 22085 Hamburg in den von uns genutzten Räumlichkeiten, Vertriebsflächen oder die von uns genutzten Kommunikationskanäle abgeben oder auf ein von uns ausgesprochenes Vertragsschlussangebot annehmend antworten.</p>
                <p>(4) Der Nutzer stimmt mit der Beauftragung bzw. Bestellung zudem – soweit vorhanden – Ihren AGB und der Datenverarbeitung gemäß dessen Datenschutzerklärung verbindlich zu.</p>
                <p>(5) Der Nutzer ist gegenüber Ihnen an die Beauftragung bzw. Bestellung für die Dauer von 2 Wochen nach Abgabe der Bestellung gebunden.</p>
                <p>(6) Sie selbst oder wir in Ihrem Namen können den Zugang der abgegebenen Bestellung bzw. Auftrages mündlich oder durch die Aushändigung einer Quittung oder jeglichen schriftlichen Bestätigung bestätigen. In einer solchen Bestätigung liegt noch keine verbindliche Annahme der Bestellung bzw. des Auftrages, es sei denn, darin wird neben der Bestätigung des Zugangs zugleich die Annahme erklärt.</p>
                <p>(7) Die Bestätigung erfolgt grundsätzlich durch Sie selbst oder durch uns in Ihrem Namen, kann aber auch durch einen Dritten – wiederum in unserem Namen als Vermittler des Anbieters erfolgen, insbesondere durch einen Vermittler oder ein Webportal, auf dem wir unsererseits ein Profil unterhalten, insbesondere wenn die Bestellung bzw. Beauftragung über das Webportal erfolgte.</p>
                <p>(8) Ein Vertrag kommt erst zustande, wenn Sie die Bestellung bzw. den Auftrag des Nutzers durch eine Annahmeerklärung annehmen, mit der Leistungserbringung beginnen, eine Rechnung stellen oder die Leistung – ganz oder teilweise – erbringen.</p>
                <p>(9) Sollte die Erbringung der vom Nutzer bestellten bzw. beauftragten Leistung nicht möglich sein, etwa, weil ein zur Erbringung erforderlicher Bestandteil der Leistung nicht erhältlich ist, sieht der Anbieter von einer Annahmeerklärung ab. In diesem Fall kommt ein Vertrag nicht zustande.</p>
                <ul>
                <li><strong> 6 Unsere Vergütung</strong></li>
                </ul>
                <p>(1) Für die Nutzung bestimmter Modelle und Funktionen unserer Plattformen und die Inanspruchnahme unserer Leistungen erhalten wir von den Anbietern eine Vergütung.</p>
                <p>(2) Unsere Vergütung und – soweit vorhanden – die besonderen Bedingungen der jeweiligen Leistungen richten sich nach den jeweils im Einzelfall in Anspruch genommenen Modellen und Funktionen.</p>
                <p>Insbesondere beträgt unsere Vergütung dabei:</p>
                <ul>
                <li>Bei der Inanspruchnahme einer Leistung eines Anbieters im Marktplatzmodell 16 % (zehn) des Netto-Rechnungsbetrags des Anbieters an den Nutzer</li>
                <li>Einen vereinbarten Preis für die Mietdauer der Regalflächen
                <ul>
                <li>Für die Dauer von 7, 14 oder 21 Tagen</li>
                </ul>
                </li>
                <li>Bei der Vermittlung eines Leads, welche insbesondere mit dem Versand von Informationsmaterialien zu einem Angebot eines Nutzers im Marktplatzmodell erfolgt, 1 % (ein) des ausgewiesenen Netto-Preises des Anbieters.</li>
                </ul>
                <p>Alle weiteren Vergütungsbestandteile richten sich daneben nach den jeweils im Einzelfall in Anspruch genommenen Modellen und Funktionen.</p>
                <p>Wir werden Sie im Rahmen des jeweiligen Vertragsschlusses ausdrücklich auf die Kostenpflicht, die anfallenden Kosten und alle weiteren besonderen Bedingungen hinweisen.</p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 7 Vertragslaufzeit und Kündigung</strong></li>
                </ul>
                <p>(1) Der Anbietervertrag zwischen uns und Ihnen läuft auf unbestimmte Zeit, soweit wir keine andere Laufzeit vereinbart haben.</p>
                <p>(2) Ist keine Mindestlaufzeit vereinbart worden, sind der Anbieter als auch wir jederzeit berechtigt, den Anbietervertrag ohne Angabe von Gründen zu kündigen. Eine Kündigung kann per Mail, per Fax oder innerhalb des Benutzerkontos erfolgen. Bei einer entgeltlichen Leistung bleibt der Anbieter trotz Kündigung zur Zahlung des vereinbarten Entgelts bis zum Vertragsende verpflichtet.</p>
                <p>(3) Damit die Kündigung per E-Mail oder Fax zugeordnet werden kann, sollten der vollständige Name, die hinterlegte E-Mail-Adresse, die Anschrift und die persönliche Kennung werden. Die Kündigung einer zusätzlichen Leistung/Option lässt den zugrunde liegenden Vertrag unberührt.</p>
                <p>(4) Im Falle einer Mindestvertragslaufzeit verlängert sich der Vertrag nach der Mindestvertragslaufzeit auf eine unbestimmte Zeit, wenn er nicht vorab mit einer Frist von einer Woche zum jeweiligen Laufzeitende im Voraus von einer der Parteien gekündigt wird. Nach der Verlängerung auf unbestimmte Zeit kann der Vertrag mit einer Frist von einer Woche zum Ende eines Monats gekündigt werden.</p>
                <p>(5) Eine sofortige außerordentliche Kündigung aus wichtigem Grund ist insbesondere bei Verstößen gegen den Anbietervertrag sowie sonstige Vertragspflichten, diese AGB, der Verletzung von Rechten Dritter, Rufschädigungen möglich. Eine erneute Anmeldung und Registrierung ist erst nach 3 Jahren nach erneutem Auswahlverfahren und ohne Rechtsanspruch möglich. Schadenersatzansprüche werden vorbehalten.</p>
                <p>(6) Jede Kündigungsart berechtigt uns zur Löschung des Kontos sowie aller von diesem erstellter bzw. hochgeladener persönlicher Daten. Personenbezogene Daten und andere Informationen, die an Sie übermittelt werden, stehen in der alleinigen Verantwortung des jeweiligen Anbieters.</p>
                <ul>
                <li><strong> 8 Widerruf</strong></li>
                </ul>
                <p>(1) Falls Sie Unternehmer im Sinne des § 14 BGB sind, besteht das Widerrufsrecht nicht. Für Verbraucher gilt:</p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>Widerrufsbelehrung</strong></p>
                <p><strong>Widerrufsrecht</strong></p>
                <p>Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen.</p>
                <p>Die Widerrufsfrist beträgt vierzehn Tage ab dem Tag des Vertragsschlusses.</p>
                <p>Um Ihr Widerrufsrecht auszuüben, müssen Sie uns (MiniFinds eGbR, Schwarzbuchenweg 49, 22391 Hamburg, 015115292977, <a href="mailto:info@minifinds.de">info@minifinds.de</a>) mittels einer eindeutigen Erklärung (z. B. ein mit der Post versandter Brief, Telefax oder E-Mail) über Ihren Entschluss, diesen Vertrag zu widerrufen, informieren. Sie können dafür das beigefügte Muster-Widerrufsformular verwenden, das jedoch nicht vorgeschrieben ist.</p>
                <p>Zur Wahrung der Widerrufsfrist reicht es aus, dass Sie die Mitteilung über die Ausübung des Widerrufsrechts vor Ablauf der Widerrufsfrist absenden.</p>
                <p><strong>Folgen des Widerrufs</strong></p>
                <p>Wenn Sie diesen Vertrag widerrufen, haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben, einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass Sie eine andere Art der Lieferung als die von uns angebotene, günstigste Standardlieferung gewählt haben), unverzüglich und spätestens binnen vierzehn Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über Ihren Widerruf dieses Vertrags bei uns eingegangen ist. Für diese Rückzahlung verwenden wir dasselbe Zahlungsmittel, das Sie bei der ursprünglichen Transaktion eingesetzt haben, es sei denn, mit Ihnen wurde ausdrücklich etwas anderes vereinbart; in keinem Fall werden Ihnen wegen dieser Rückzahlung Entgelte berechnet.</p>
                <p>Haben Sie verlangt, dass die Dienstleistungen während der Widerrufsfrist beginnen soll, so haben Sie uns einen angemessenen Betrag zu zahlen, der dem Anteil der bis zu dem Zeitpunkt, zu dem Sie uns von der Ausübung des Widerrufsrechts hinsichtlich dieses Vertrags unterrichten, bereits erbrachten Dienstleistungen im Vergleich zum Gesamtumfang der im Vertrag vorgesehenen Dienstleistungen entspricht.</p>
                <p><strong>Muster-Widerrufsformular</strong></p>
                <p>(Wenn Sie den Vertrag widerrufen wollen, dann füllen Sie bitte dieses Formular aus und senden Sie es zurück.)</p>
                <p>— An MiniFinds eGbR, Schwarzbuchenweg 49, 22391 Hamburg, 015115292977, <a href="mailto:info@minifinds.de">info@minifinds.de</a>:</p>
                <p>— Hiermit widerrufe(n) ich/wir (*) den von mir/uns (*) abgeschlossenen Vertrag über die Erbringung der folgenden Dienstleistung (*)</p>
                <p>— Bestellt am (*) / erhalten am (*)</p>
                <p>— Name des/der Verbraucher(s)</p>
                <p>— Anschrift des/der Verbraucher(s)</p>
                <p>— Unterschrift des/der Verbraucher(s) (nur bei Mitteilung auf Papier)</p>
                <p>— Datum _______________ (*)</p>
                <p>-&nbsp;&nbsp;&nbsp; ENDE DIESES MUSTERWIDERRUFSFORMULARS -</p>
                <p>(2) Das Widerrufsrecht besteht nicht, erlischt oder kann ausgeschlossen werden, wenn ein gesetzlich geregelter Fall, eine entsprechende gerichtliche Entscheidung oder ein sonstiger rechtlicher Grund besteht. Gesetzlich geregelte Fälle ergeben sich insbesondere aus §§ 312 g oder 356 BGB.</p>
                <ul>
                <li>bei Verträgen, zur Erbringung von Dienstleistungen in den Bereichen der Beherbergung zu anderen Zwecken als zu Wohnzwecken, Beförderung von Waren, Kraftfahrzeugvermietung, Lieferung von Speisen und Getränken sowie zur Erbringung weiterer Dienstleistungen im Zusammenhang mit Freizeitbetätigungen, wenn der Vertrag für die Erbringung einen spezifischen Termin oder Zeitraum vorsieht.</li>
                </ul>
                <p>(3) Das Widerrufsrecht erlischt bei einem Vertrag über die Lieferung von nicht auf einem körperlichen Datenträger befindlichen digitalen Inhalten auch dann, wenn der Unternehmer mit der Ausführung des Vertrags begonnen hat, nachdem der Verbraucher</p>
                <p>1. ausdrücklich zugestimmt hat, dass der Unternehmer mit der Ausführung des Vertrags vor Ablauf der Widerrufsfrist beginnt, und</p>
                <p>2. seine Kenntnis davon bestätigt hat, dass er durch seine Zustimmung mit Beginn der Ausführung des Vertrags sein Widerrufsrecht verliert.</p>
                <p>(4) Das Widerrufsrecht erlischt insbesondere bei einem Vertrag zur Erbringung von Dienstleistungen auch dann, wenn der Unternehmer die Dienstleistung vollständig erbracht hat und mit der Ausführung der Dienstleistung erst begonnen hat, nachdem der Verbraucher dazu seine ausdrückliche Zustimmung gegeben hat und gleichzeitig seine Kenntnis davon bestätigt hat, dass er sein Widerrufsrecht bei vollständiger Vertragserfüllung durch den Unternehmer verliert. Bei einem außerhalb von Geschäftsräumen geschlossenen Vertrag muss die Zustimmung des Verbrauchers auf einem dauerhaften Datenträger übermittelt werden. Bei einem Vertrag über die Erbringung von Finanzdienstleistungen erlischt das Widerrufsrecht abweichend von Satz 1, wenn der Vertrag von beiden Seiten auf ausdrücklichen Wunsch des Verbrauchers vollständig erfüllt ist, bevor der Verbraucher sein Widerrufsrecht ausübt.</p>
                <ul>
                <li><strong> 9 Mitwirkungspflicht</strong></li>
                </ul>
                <p>(1) Sie werden uns bei der Erbringung unserer vertragsgemäßen Leistungen durch angemessene Mitwirkungshandlungen fördern. Sie werden uns beispielsweise die erforderlichen Informationen, Daten, Umstände, Verhältnisse unverzüglich mitteilen; Unterlagen, Materialien, Sachen oder Zugänge zur Erfüllung der Leistung zur Verfügung stellen; uns unverzüglich Weisungen und Freigaben erteilen und uns einen kompetenten Ansprechpartner benennen, der nicht ausgewechselt wird. Sie müssen zu Ihren Handlungen – insbesondere zu Überlassungen oder Zugangsgewährungen - berechtigt sein, insbesondere dürfen keine Rechte Dritter oder behördliche Bestimmungen verletzt werden. Sie sind ferner verpflichtet, aktiv an der Vermeidung und Aufklärung von Schäden mitzuwirken, die durch Diebstahl, Beschädigung, Verlust sowie durch Wasser, Feuer oder Einbruch entstehen könnten. Insbesondere sind Sie dazu angehalten, angemessene Schutzmaßnahmen zu ergreifen, um solche Schäden zu verhindern.</p>
                <p>(2) Soweit Sie zur Mitteilung, Bereitstellung oder zur Verfügungsüberlassung nach Abs. 1 nicht berechtigt sind, beispielsweise weil wettbewerbs-, datenschutz-, marken- und kennzeichenrechtliche Verstöße oder jegliche Verstöße gegen Rechte Dritter oder behördliche Bestimmungen vorliegen, liegt ebenso fehlende Mitwirkung vor. Sie versichern Ihre Berechtigung zu den entsprechenden Handlungen. Eine entsprechende Überprüfung durch uns wird nicht erfolgen. Von etwaigen Ansprüchen Dritter, die wegen Ihrer fehlenden Berechtigung gegen uns vorgehen, werden Sie uns auf erstes Anfordern freistellen und uns jeglichen Schaden, der wegen der Inanspruchnahme durch den Dritten entsteht, einschließlich etwaiger für die Rechtsverteidigung anfallenden Gerichts- und Anwaltskosten, ersetzen. Im Übrigen gelten die gesetzlichen Bestimmungen.</p>
                <p>(3) Fehlende, unvollständige, schadensverursachende oder rechtsverletzende Mitwirkung – beispielsweise durch Mitteilung bzw. Zuleitung unvollständiger, unrichtiger oder nicht zur rechtmäßigen Verwendung geeigneter Informationen, Daten, Stoffe oder Unterlagen – berechtigt uns zur Beendung des Vertrags, im Falle eines Vertrages mit einem Unternehmer auch ohne Auswirkung auf die vereinbarte Vergütung.</p>
                <p>(4) Entsteht uns durch fehlerhafte Mitwirkung ein Schaden, besteht ein Schadensersatzanspruch. Sie stellen uns in diesem Fall ebenso von sämtlichen Ansprüchen Dritter frei, die Dritte im Zusammenhang mit von Ihnen zumindest grob fahrlässig fehlerhaft durchgeführten Mitwirkungshandlungen geltend machen.</p>
                <p>(5) Das Einräumen der gemieteten Regalflächen steht Ihnen nach vorzeigen eines gültigen Personalausweises</p>
                <ul>
                <li>am Tag vor dem Beginn Ihres Mietzeitraums von 17 – 18 Uhr – sofern dieser Tag auf einen Tag von Montag – Freitag fällt;</li>
                <li>am Tag vor dem Beginn Ihres Mietzeitraums von 15 – 16 Uhr – sofern dieser Tag auf einen Samstag fällt;</li>
                <li>ganztätig während der Mietzeit</li>
                </ul>
                <p>zur Verfügung. Entsprechendes gilt für das Ausräumen der gemieteten Regalflächen.</p>
                <p>(6) Sollten Sie die gemieteten Regalflächen nicht innerhalb der in Abs. 5 genannten Zeiten ausräumen, sind wir berechtigt, Ihnen eine Ausräumgebühr in Höhe von 50 € in Rechnung zu stellen.</p>
                <p>Darüber hinaus behalten wir uns vor, die von Ihnen nicht rechtzeitig entfernten Gegenstände, Artikel die keinem Stand zuzuordnen, vergessen oder nicht abgeholt werden; nach einer Aufbewahrungsfrist von 14 Tagen Ihre Produkte an eine wohltätige Organisation zu spenden, auf Ihre Kosten einzulagern oder zu entsorgen, sofern keine anderweitige Vereinbarung getroffen wurde. Die gesetzlichen Ansprüche auf Schadensersatz bleiben hiervon unberührt.</p>
                <ul>
                <li><strong> 10</strong> <strong>Kommunikation</strong></li>
                </ul>
                <p>(1) Zur Gewährleistung einer schnellen und einfachen Kommunikation untereinander erfolgt die Kommunikation grundsätzlich über E-Mail sowie Ihr Konto auf unseren Plattformen. Sie willigen dazu ein, dass Ihnen Informationen per E-Mail, soweit vorhanden Ihrem Konto auf unseren Plattformen, postalisch oder auf anderem Weg zugesandt werden.</p>
                <p>(2) Der Versand und die Kommunikation erfolgen auf Ihr Risiko. Für Störungen in den Leitungsnetzen des Internets, für Server- und Softwareprobleme Dritter oder Probleme eines Post- oder Zustellungsdienstleisters sind wir nicht verantwortlich und haften nicht.</p>
                <ul>
                <li><strong> 11 Technische Verfügbarkeit, Daten, Funktionalität und Inhalte</strong></li>
                </ul>
                <p>(1) Die Online-Plattformen sind 24 Stunden am Tag, 7 Tage die Woche zugänglich, außer im Fall höherer Gewalt oder einem außerhalb unseres Einflusses liegenden Ereignis und vorbehaltlich von Ausfällen und Wartungsarbeiten, die für den Betrieb erforderlich sind. Für unsere lokalen Räumlichkeiten (offline Plattformen) gelten die entsprechenden örtliche Öffnungszeiten. Wir wirken mit großer Sorgfalt auf eine höchstmögliche Erreichbarkeit hin. Die Verfügbarkeit hängt unter anderem von Ihrer technischen Ausstattung ab. Verfügbarkeitsunterbrechungen können durch notwendige Wartungs- und Sicherheitsarbeiten oder unvorhergesehen Ereignissen eintreten, die nicht in unserem Einflussbereich liegen.</p>
                <p>(2) Wir haften nicht für Ihren Verlust von Daten oder von daraus resultierenden Schäden, soweit die Schäden durch eine regelmäßige und vollständige Sicherung der Daten bei Ihnen nicht eingetreten wären.</p>
                <p>(3) Wir können jegliche Funktionsweise, das Aussehen, den Aufbau oder die Inhalte unserer Plattformen verändern, ohne Ihre Zustimmung einzuholen.</p>
                <p>(4) Wir sind berechtigt, alle Inhalte – auch User-Generated-Content - zu sperren oder zu verändern.</p>
                <ul>
                <li><strong> 12 Rechteeinräumung an Daten</strong></li>
                </ul>
                <p>(1) Sie verpflichten sich, über die Plattformen keine Texte, Bilder, Video, Audiodateien und/oder sonstige Inhalte („Dateien“) zu verbreiten, die gegen geltendes Recht, gegen die guten Sitten und/oder gegen diese AGB verstoßen. Sie verpflichten sich insbesondere, die Rechte Dritter, wie Urheberrechte, Markenrechte, Patent- und Gebrauchsmusterrechte, Designrechte, Datenbankrechte sowie jegliche sonstigen gewerblichen Schutzrechte (nachstehend „Schutzrechte“), zu beachten.</p>
                <p>(2) Sie räumen uns hiermit ein umfassendes, ausschließliches, räumlich und zeitlich unbegrenztes und für alle Nutzungsarten uneingeschränkt geltendes Nutzungsrecht an den Dateien bzw. Schutzrechten ein, die Sie über unsere Plattformen veröffentlichen oder auf unsere Plattform oder in das Nutzerkonto hochladen oder uns auf jede andere Weise zuleiten, insbesondere das Nutzungsrecht an Ihrem Bild, Ihrem Namen bzw. Unternehmensnamen, Ihrer Marke und jeglichen anderen Materialien. Soweit dies nach anwendbarem Recht möglich ist, verzichten Sie hiermit unbedingt und unwiderruflich auf alle Urheberpersönlichkeitsrechte, die an den Dateien bestehen, inklusive des Namensnennungsrechts und des Entstellungsverbots.</p>
                <p>(3) Die Rechteeinräumung umfasst insbesondere das Recht, die Dateien für eigene oder fremde Zwecke in jeder Weise weltweit und zeitlich unbefristet zu verwerten, einschließlich der Verwertung in und auf Produkten, ob eigene oder solche für Dritte, in allen Verwendungsarten. Sie umfasst außerdem das Recht, die Dateien zu vervielfältigen und/oder zu veröffentlichen. Zu den Rechten gehört auch das Bearbeitungsrecht, dh die Berechtigung, die Dateien weiter zu bearbeiten oder durch Dritte weiter zu bearbeiten lassen.</p>
                <p>(4) Soweit wir Dateien für Sie erstellen, verbleiben sämtliche Urheber- und Nutzerrechte bei uns.</p>
                <ul>
                <li><strong> 13 Unsere Rechte an unseren Plattformen</strong></li>
                </ul>
                <p>(1) Sie erklären sich einverstanden, dass es sich bei den Plattformen und allen mit ihnen zusammenhängenden Anwendungen um Datenbankwerke und um Datenbanken i. S. v. §§ 4 Abs. 2, 87a Abs. 1 UrhG handelt, deren rechtliche Inhaber wir sind. Alle zugehörigen Anwendungen unterfallen dem Schutz nach §§ 69a ff. UrhG. Sie sind urheberrechtlich geschützt.</p>
                <p>(2) Die Rechte an allen sonstigen Elementen unserer Plattformen, insbesondere die Nutzungs- und Leistungsschutzrechte an den von uns eingestellten oder per Rechteeinräumung erworbenen Inhalten und Dokumenten, stehen ebenfalls ausschließlich uns zu. Insbesondere Marken, sonstige Kennzeichen, Firmenlogos, Schutzvermerke, Urhebervermerke oder andere der Identifikation unserer Plattformen dienender einzelner Elemente davon dienende Merkmale dürfen nicht entfernt oder verändert werden. Das gilt ebenso für Ausdrucke.</p>
                <ul>
                <li><strong> 14 Änderung der Dienste</strong></li>
                </ul>
                <p>Wir behalten uns vor, den zur Inanspruchnahme unserer Leistungen erforderlichen Zugriff auf Software, Online-Datenbanken, Funktionen, Betriebssysteme, Dokumentationen und alle anderen Bestandteile unserer Software sowie ihre Funktionsweise– soweit rechtlich zulässig auch ohne vorherige Ankündigung – insgesamt oder in Teilen, jederzeit, vorübergehend oder auf Dauer, einzustellen, zu verändern, oder einzuschränken. Insbesondere behalten wir uns vor, Eigenschaften unserer Leistungen (beispielsweise Design, Layout, Rubriken, Struktur oder Verfügbarkeit) zu verändern, zu deaktivieren, kostenfreie Bestandteile in kostenpflichtige umzustellen, bestimmte Funktionen nicht weiter zu unterstützen oder die Komptabilität (beispielsweise zu bestimmten Gerätetypen oder Betriebssystemen) auszusetzen.</p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 15 Endbenutzer-Lizenzvertrag (EULA)</strong></li>
                </ul>
                <p>(1) Wir gewähren Ihnen ein persönliches, nicht exklusives, widerrufliches, nicht übertragbares und weltweites Nutzungsrecht an den Plattformen - insbesondere jeglichen Softwarefunktionen auf der Webseite oder Apps -, ihren Inhalten, Diensten, sonstigen Funktionen und allen Updates. Dieses wird ausschließlich für Ihren eigenen Bedarf und im Rahmen der Nutzung der Plattformen und deren Diensten und unter Ausschluss jeglicher anderen Zwecke gewährt.</p>
                <p>(2) Unsere digitalen Produkte (insbesondere Apps, Software) werden an Sie lizenziert und nicht an Sie verkauft.</p>
                <p>(3) Die Lizenz gibt ihnen kein Nutzungsrecht am Inhalt. Es ist insbesondere verboten:</p>
                <ul>
                <li>Die Plattformen, ihre Inhalte, Dienste, sonstige Funktionen oder Updates anzupassen, zu verändern, zu übersetzen, zu bearbeiten, eine Rückumstellung vorzunehmen, zu zerlegen, zu transkodieren oder durch Reverse Engineering die Plattform oder einen Teil davon abzubilden;</li>
                <li>Die Plattformen, ihre Inhalte, Dienste, sonstige Funktionen oder Updates zu exportieren, oder ganz oder teilweise mit anderen Softwareprogrammen zu verbinden, oder sie ganz oder teilweise, mit jeglichem Mittel und in jeglicher Form dauerhaft oder vorläufig zu reproduzieren;</li>
                <li>Inhalte der Datenbanken, die aus den Plattformen entstanden sind, zu extrahieren oder weiterzuverwenden;</li>
                <li>Werke zu erstellen, die von der lizenzierten Plattform abgeleitet sind;</li>
                <li>Prozesse oder Software zu nutzen, die dazu bestimmt sind, die Plattformen, ihre Inhalte, Dienste, sonstige Funktionen oder Updates ohne unsere Zustimmung zu kopieren;</li>
                <li>Systeme einzurichten, die imstande sind, die Plattformen zu hacken.</li>
                <li>Dritten unsere Leistungen ohne unsere Zustimmung anzubieten oder zu überlassen.</li>
                </ul>
                <p>(4) Bei einer Verletzung des Verbots bestehen Strafbarkeit und Schadensersatzpflicht.</p>
                <ul>
                <li><strong> 16 Werbung Dritter</strong></li>
                </ul>
                <p>(1) Wir behalten uns das Recht vor, Ihnen auf unseren Plattformen Werbung Dritter anzuzeigen. Wir haben keinen Einfluss auf die Werbung, insbesondere nicht auf ihren Inhalt, ihre Zuverlässigkeit oder ihre Genauigkeit. Die Anzeige von Werbung erfolgt ohne unsere Prüfung, insbesondere wird sie von uns inhaltlich nicht gebilligt – verantwortlich ist alleine Werbetreibende. Bei jeder Form der Beanspruchung – insbesondere durch Klicken, Nutzung ihrer mittels application programming interface („API“) durchgeführten Leistungen oder dem Besuch ihrer auf der Werbung verlinkten Plattformen – gelten ihre Vertragsbedingungen, AGB und Datenschutzbestimmungen.</p>
                <p>(2) Werbung kann insbesondere mit der Verlinkung von Plattformen Dritter oder API-Anwendungen Dritter einhergehen. Auch hierbei besteht alleine die Verantwortlichkeit des jeweiligen Anbieters der Werbung. Es gelten dessen Vertragsbedingungen, AGB und Datenschutzbestimmungen.</p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 17 Urheber- und sonstige Rechte</strong></li>
                </ul>
                <p>Wir haben an allen Bildern, Filmen, Texten und sonstigen vom Urheberrecht oder ähnlichen Rechten, insbesondere durch geistige Eigentumsrechte, geschützten Inhalten, die auf unserer Webseite, unseren Profilen auf anderen Webseiten, unseren Social-Media-Profilen und allen unseren Plattformen veröffentlicht werden, Urheberrechte oder sonstige Rechte. Eine Verwendung der Bilder, Filme, Texte und sonstiger Rechte ist ohne unsere schriftliche Zustimmung nicht gestattet.</p>
                <ul>
                <li><strong> 18 Datenschutz und Datensicherheit</strong></li>
                </ul>
                <p>(1) Wir erheben personenbezogene Daten von Ihnen sowie ggf. andere, von Ihnen zugeleitete oder im Zuge der Vertragserfüllung von uns erlangte Daten zum Zweck der Vertragsdurchführung sowie zur Erfüllung der vertraglichen und vorvertraglichen Pflichten. Die Datenerhebung und Datenverarbeitung ist zur Vertragserfüllung erforderlich und beruht auf Artikel 6 Abs.1 b) DSGVO. Wir verarbeiten sie nach den Verpflichtungen der DSGVO. Nach § 5 Abs. 1 DSGVO müssen personenbezogene Daten im Wesentlichen:</p>
                <p>(a) auf rechtmäßige und faire Weise und in einer für die betroffene Person nachvollziehbaren Weise verarbeitet werden („Rechtmäßigkeit, Verarbeitung nach Treu und Glauben, Transparenz“);</p>
                <p>(b) für festgelegte, eindeutige und legitime Zwecke erhoben werden und dürfen nicht in einer mit diesen Zwecken nicht zu vereinbarenden Weise weiterverarbeitet werden („Zweckbindung“);</p>
                <p>(c) dem Zweck angemessen und erheblich sowie auf das für die Zwecke der Verarbeitung notwendige Maß beschränkt sein („Datenminimierung“);</p>
                <p>(d) sachlich richtig und erforderlichenfalls auf dem neuesten Stand sein; es sind alle angemessenen Maßnahmen zu treffen, damit personenbezogene Daten, die im Hinblick auf die Zwecke ihrer Verarbeitung unrichtig sind, unverzüglich gelöscht oder berichtigt werden („Richtigkeit“);</p>
                <p>(e) in einer Form gespeichert werden, die die Identifizierung der betroffenen Personen nur so lange ermöglicht, wie es für die Zwecke, für die sie verarbeitet werden, erforderlich ist („Speicherbegrenzung“);</p>
                <p>(f) in einer Weise verarbeitet werden, die eine angemessene Sicherheit der personenbezogenen Daten gewährleistet, einschließlich Schutz vor unbefugter oder unrechtmäßiger Verarbeitung und vor unbeabsichtigtem Verlust, unbeabsichtigter Zerstörung oder unbeabsichtigter Schädigung durch geeignete technische und organisatorische Maßnahmen („Integrität und Vertraulichkeit“).</p>
                <p>(2) Daten werden grundsätzlich nicht an Dritte übermittelt, wenn keine entsprechende Pflicht besteht oder die Vertragsdurchführung oder der Einhaltung einer gesetzlichen Frist eine Datenübermittlung erforderlich macht, beispielsweise wenn die Weitergabe der Daten erforderlich sind, um für Sie eine zur Vertragsdurchführung notwendige Abfrage durch einen Drittanbieter durchzuführen, Ihre Daten an einen Zahlungsanbieter weitergeleitet werden oder Subunternehmer in Anspruch genommen werden, um zur Erfüllung einer Leistungspflicht Ihnen gegenüber beizutragen. In diesen Fällen werden die Dienstleister vielfach mit Ihnen ein Vertragsverhältnis haben, so dass sie auf eigene Verantwortung handeln.</p>
                <p>(3) Sobald Daten für den Zweck ihrer Verarbeitung nicht mehr erforderlich sind und falls eine gesetzliche Aufbewahrungspflicht nicht weiter besteht, werden sie von uns gelöscht. In Anbahnung unseres Vertragsverhältnisses sowie bei dessen Durchführung bewahren wir Ihre Daten auf. Dabei kann es auch notwendig sein, dass nach Kündigung unseres Vertragsverhältnisses Daten weiter aufbewahrt werden. Beispielsweise müssen Rechnungsdaten (Abrechnungsunterlagen) gemäß § 147 Abgabenordnung 10 Jahre aufbewahrt werden. Solange ein für uns ausführender Dienstleister ebenso einen Vertrag über die Durchführung Ihrer Leistung mit uns hat, bleiben wir verpflichtet, die Daten entsprechend der vereinbarten Aufbewahrungsfristen vorzuhalten.</p>
                <p>(4) Sie haben das Recht auf Auskunft, Datenübertragung, Löschung, Berichtigung, Einschränkung oder Sperrung Ihrer personenbezogenen Daten. Insbesondere haben Sie einen Anspruch auf eine unentgeltliche Auskunft über alle personenbezogenen Daten.</p>
                <p>Ihre Anfrage kann an uns gestellt werden. Außerdem stehen Ihnen entsprechende verwaltungsrechtliche oder gerichtliche Rechtsbehelfe oder die bei einer Aufsichtsbehörde offen.</p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 19 Haftung, Freistellung und Aufwendungsersatz</strong></li>
                </ul>
                <p>(1) Wir haften gegenüber Ihnen in allen Fällen vertraglicher und außervertraglicher Haftung bei Vorsatz und grober Fahrlässigkeit nach Maßgabe der gesetzlichen Bestimmungen auf Schadensersatz oder Ersatz vergeblicher Aufwendungen.</p>
                <p>(2) In sonstigen Fällen haften wir – soweit in Abs. 3 nicht abweichend geregelt – nur bei Verletzung einer Vertragspflicht, deren Erfüllung die ordnungsgemäße Durchführung des Vertrags überhaupt erst ermöglicht und auf deren Einhaltung Sie als Vertragspartner regelmäßig vertrauen dürfen (so genannte Kardinalpflicht), und zwar beschränkt auf den Ersatz des vorhersehbaren und typischen Schadens. In allen übrigen Fällen ist unsere Haftung vorbehaltlich der Regelung in Abs. 3 ausgeschlossen.</p>
                <p>(3) Unsere Haftung für Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit und nach dem Produkthaftungsgesetz bleibt von den vorstehenden sowie allen übrigen in diesen AGB sowie zwischen uns getroffenen Haftungs-, Gewährleistungs- oder Verantwortungsbeschränkungen und Haftungs-, Gewährleistungs- oder Verantwortungsausschlüssen unberührt.</p>
                <p>(4) Sie stellen uns von etwaigen Ansprüchen Dritter, die wegen möglicher schuldhafter Verletzungen des Partners gegen seine Pflichten – insbesondere aus diesen AGB – gegen uns und/oder unseren Erfüllungsgehilfen geltend gemacht werden, auf erstes Anfordern frei. Sie ersetzen uns jeglichen Schaden, der wegen der Inanspruchnahme durch den Dritten entsteht, einschließlich etwaiger für die Rechtsverteidigung anfallenden Gerichts- und Anwaltskosten. Im Übrigen gelten die gesetzlichen Bestimmungen.</p>
                <p>(5) Wir haben Anspruch auf Ersatz der Aufwendungen, die wir den Umständen nach für erforderlich halten durften und nicht zu vertreten hatten, insbesondere jegliche Aufwendungen zum Schutz des Vertragsgutes sowie daneben auf eine ortsübliche, angemessene Vergütung.&nbsp;</p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 20 Leistungsort, Anwendbares Recht, Vertragssprache und Gerichtsstand</strong></li>
                </ul>
                <p>(1) Für alle Leistungen aus dem Vertrag wird als Erfüllungsort Hamburg vereinbart.</p>
                <p>(2) Es gilt das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts. Sind sowohl Sie als auch wir zum Zeitpunkt des Vertragsschlusses Kaufleute und haben Sie Ihren Sitz zum Zeitpunkt des Vertragsschlusses in Deutschland, ist ausschließlicher Gerichtsstand unser Sitz in Hamburg. Im Übrigen gelten für die örtliche und die internationale Zuständigkeit die anwendbaren gesetzlichen Bestimmungen.</p>
                <p>(3) Vertragssprache ist, soweit nichts Anderes schriftlich vereinbart ist, Deutsch. Jegliche übersetzten Rechtstexte oder Dokumente dienen alleine einem besseren Verständnis. Insbesondere in Bezug auf eine Vertragsabrede als auch auf diese AGB, die Datenschutzbestimmungen oder alle anderen Rechtstexte oder Dokumente sind die deutschen Versionen rechtsverbindlich; dies gilt insbesondere bei Abweichungen oder Auslegungsunterschieden zwischen solchen Rechtstexten oder Dokumenten.</p>
                <p>(4) In Bezug auf Streitigkeiten mit Verbrauchern hat die&nbsp; &nbsp;EU-Kommission eine Internetplattform zur Online-Streitbeilegung geschaffen – die alternative Streitbeilegung nach der ODR-Verordnung und § 36 VSBG. Diese Plattform dient als Anlaufstelle zur außergerichtlichen Beilegung von Streitigkeiten betreffend vertragliche Verpflichtungen, die aus Online-Kaufverträgen erwachsen. Nähere Informationen sind unter dem folgenden Link verfügbar: <a href="https://ec.europa.eu/consumers/odr"></a><a href="http://ec.europa.eu/consumers/odr">http://ec.europa.eu/consumers/odr</a></p>
                <p>Die Teilnahme an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle ist nicht verpflichtend und wird von uns nicht wahrgenommen.</p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <p><strong>&nbsp;</strong></p>
                <ul>
                <li><strong> 20 Schlussbestimmungen</strong></li>
                </ul>
                <p>(1) Änderungen und Ergänzungen dieser AGB erfolgen schriftlich, das Recht hierzu behalten wir uns vor. Änderungen setzen voraus, dass Sie nicht unangemessen benachteiligt werden, kein Verstoß gegen Treu und Glauben geschieht und der Änderung nicht widersprochen wird. Im Fall einer Änderung erfolgt eine Mitteilung über einen der Kommunikationskanäle – insbesondere per E-Mail – 2 Monate vor ihrer Wirksamkeit. Die Änderung wird wirksam, wenn ihr nicht innerhalb dieser Frist widersprochen wird – hiernach werden die geänderten AGB gültig.</p>
                <p>(2) Eine Abtretung dieses Vertrags an ein anderes Unternehmen wird vorbehalten. Sie wird 1 Monat nach Absendung einer Abtretungsmitteilung über einen unserer Kommunikationskanäle – insbesondere per E-Mail – an Sie gültig. Sie haben im Fall einer Abtretung ein Kündigungsrecht, welches 1 Monat nach Zugang der Mitteilung der Abtretung gilt. Alle uns eingeräumten Rechte gelten zugleich als unseren Rechtsnachfolgern eingeräumt.</p>
                <p>(3) Im Falle der Unwirksamkeit einzelner Bestimmungen dieser AGB, wird die Rechtswirksamkeit der übrigen Bestimmungen nicht berührt. Die unwirksame Bestimmung wird durch eine wirksame Bestimmung ersetzt, die dem beabsichtigten wirtschaftlichen Zweck am nächsten kommt.</p>
                </div>
            </div>
        </div>


        </div>
    </div>
</div>