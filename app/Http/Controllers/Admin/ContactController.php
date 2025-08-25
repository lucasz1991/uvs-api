<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\ProcessManager;
use DOMDocument;



class ContactController extends Controller
{


    public function analyzehtml($file)
    {
        if (!$file) {
            return;
        }

        // Datei-Inhalt lesen
        $htmlContent = file_get_contents($file->getRealPath());

        // HTML mit DOMDocument parsen
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent, LIBXML_NOWARNING | LIBXML_NOERROR); // Fehler unterdrücken

        // Alle <article class="mod mod-Treffer"> Nodes sammeln
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//article[contains(@class, "mod-Treffer")]');

        $articles = [];

        foreach ($nodes as $node) {
            $articles[] = [
                'text' => trim($node->textContent), // gesamter Text
                'html' => $dom->saveHTML($node), // HTML des Elements
                'link' => $this->extractLink($node), // Falls Links enthalten sind
            ];
        }
        return $articles;
    }

    private function extractLink($node)
    {
        $links = $node->getElementsByTagName('a');
        if ($links->length > 0) {
            return $links->item(0)->getAttribute('href'); // Erster Link im Artikel
        }
        return null;
    }

    public function analyzeArticles($articles)
    {
        include_once 'simple_html_dom.php';
        $tempContacts = [];
    
        foreach ($articles as $article) {
            $url = $article['link'];
            $html = str_get_html($this->getHtml($url));
    
            $tempContacts[] = [
                'Branche'    => $html->find('div.mod-TeilnehmerKopf__branchen', 0) ? 
                                $html->find('div.mod-TeilnehmerKopf__branchen', 0)->plaintext : 'Keine Branche',
    
                'Name'       => $html->find('h1.mod-TeilnehmerKopf__name', 0) ? 
                                $html->find('h1.mod-TeilnehmerKopf__name', 0)->plaintext : 'Kein Name',
    
                'Anschrift'  => $html->find('address.mod-TeilnehmerKopf__adresse', 0) ? 
                                $html->find('address.mod-TeilnehmerKopf__adresse', 0)->plaintext : 'Keine Adresse',
    
                'Tel_Nummer' => $html->find('span[data-role=telefonnummer]', 0) ? 
                                $html->find('span[data-role=telefonnummer]', 0)->plaintext : 'Keine Telefonnummer',
    
                'mail'       => $html->find('div#email_versenden', 0) ? 
                                $this->getMailPlain($html->find('div#email_versenden', 0)->getAttribute('data-link')) : 'Keine E-Mail',

    
                'website'    => $html->find('a[data-wipe-realview=detailseite_aktionsleiste_webadresse]', 0) ? 
                                $html->find('a[data-wipe-realview=detailseite_aktionsleiste_webadresse]', 0)->getAttribute('href') : 'Keine Website',
            ];
        }
        return $tempContacts;
    }

    private function getMailPlain($stringWithMailto) {
        $ohnemailto = substr ($stringWithMailto,7);
        $mail = substr ($ohnemailto,0,stripos($ohnemailto,'?subject'));
        return $mail;
    }

    private function getTelPlain($stringWithTel) {
        $tel = substr ($stringWithTel,4);
        return $tel;
    }
    

    private function getHtml($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if(!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, null);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    public function searchContacts2($what ,$where)
    {
      include_once 'simple_html_dom.php';

      function getHtml($url, $post = null) {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          if(!empty($post)) {
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
          }
          $result = curl_exec($ch);
          curl_close($ch);
          return $result;
      }

      function getarticles($domhtml){
          $array = array();
          $domarticles = $domhtml->find('article');
          foreach($domarticles as $element){
            $mailhtml = null !== $element->find('a[class=contains-icon-email gs-btn]',0) ? $element->find('a[class=contains-icon-email gs-btn]',0)->href : '';
            $ohnemailto = substr ($mailhtml,7);
            $mail = substr ($ohnemailto,0,stripos($ohnemailto,'?subject'));
            $website = null !== $element->find('a[class=contains-icon-homepage gs-btn]',0) ? $element->find('a[class=contains-icon-homepage gs-btn]',0)->href : '';
            $anschriftmittags = null !== $element->find('p[data-wipe-name=Adresse]',0) ? strip_tags ($element->find('p[data-wipe-name=Adresse]',0)->innertext) : '';
            $anschrift = str_replace("\t","",$anschriftmittags);
            $namemittags = null !== $element->find('h2[data-wipe-name=Titel]',0) ? strip_tags ($element->find('h2[data-wipe-name=Titel]',0)->innertext) : '';
            $name = str_replace("&amp;","&",$namemittags);
            array_push ( $array , Array(
              'Branche'=> null !== $element->find('p[class=d-inline-block mod-Treffer--besteBranche]',0) ? $element->find('p[class=d-inline-block mod-Treffer--besteBranche]',0)->innertext : '' ,
              'Name'=> $name,
              'Anschrift'=> $anschrift,
              'Tel_Nummer'=> null !== $element->find('p[data-wipe-name=Kontaktdaten]',0) ? $element->find('p[data-wipe-name=Kontaktdaten]',0)->innertext : '',
              'mail'=> $mail,
              'website'=> $website,
            ));
          }
          return $array;
      }

      $postdata = array(
         'WAS' => $what,
         'WO' => $where,
      );
      $html = str_get_html(getHtml('https://www.gelbeseiten.de/Suche', $postdata ));
      $articles = getarticles($html);
      $nextpage = null !== $html->find('a[class=gs_paginierung__sprungmarke gs_paginierung__sprungmarke--vor btn btn-default]',0) ? $html->find('a[class=gs_paginierung__sprungmarke gs_paginierung__sprungmarke--vor btn btn-default]',0)->href : null;
      for (;isset($nextpage);){
        $html = str_get_html(getHtml($nextpage));
        $rawarticles = getarticles($html);
        foreach ($rawarticles as $rawarticle) {
            array_push($articles,$rawarticle);
        }
        $nextpage = null !== $html->find('a[class=gs_paginierung__sprungmarke gs_paginierung__sprungmarke--vor btn btn-default]',0) ? $html->find('a[class=gs_paginierung__sprungmarke gs_paginierung__sprungmarke--vor btn btn-default]',0)->href : null;
      }
        return compact('articles');
    }

    public function getexcel(){
      
    }
}
