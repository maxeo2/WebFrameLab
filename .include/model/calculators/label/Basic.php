<?php

class Basic_label extends Calculator {

    private $costoPoliestere;
    private $costoAntiSpappolo;
    private $costoPoliestereTraspBiancoAvviamento;
    private $costoCarta;
    private $costoAvviamento;
    private $prezzoMin;
    private $spaziatura;
    private $larghezzaFoglio;
    private $altezzaFoglio;
    private $prezzoMinFoglio;
    private $prezzoMaxFoglio;
    private $maxEtichetteFoglio;
    private $costoAvviamentoSerigrafia;
    private $costoAvviamentoCaldo;
    private $costoAvviamentoRilievo;
    private $costoSerigrafiaMq;
    private $costoCaldoMq;
    private $costoRilievoMq;
    private $rincaroAntispappolo;
    private $rincaroPercentuale;
    private $scontoAvvolgimentoMano;
    //inizio variabili "mutevoli"
    private $label_price = 0;
    private $error = 0;
    private $prezzoSupporto;
    private $etichetteFoglio;
    private $numFogli;
    private $rincaro = 0;
    private $scontoMultilavorazione;
    private $spedizioneH24;
    private $spedizioneH72;

    public function __construct() {

        $this->costoPoliestere = 2.0;
        $this->costoAntiSpappolo = 1.4;
        $this->costoPoliestereTraspBiancoAvviamento = 140;
        $this->costoCarta = 1.0;
        $this->costoAvviamento = 18.0;
        $this->prezzoMin = 21.0;
        $this->spaziatura = 5;
        $this->larghezzaFoglio = 290;
        $this->altezzaFoglio = 1200;
        $this->prezzoMinFoglio = 1.0;
        $this->prezzoMaxFoglio = 10.0;
        $this->maxEtichetteFoglio = 1000;
        $this->costoAvviamentoSerigrafia = 190;
        $this->costoAvviamentoCaldo = 120;
        $this->costoAvviamentoRilievo = 100;
        $this->costoSerigrafiaMq = 2;
        $this->costoCaldoMq = 1;
        $this->costoRilievoMq = 1;
        $this->rincaroAntispappolo = 0;
        $this->rincaroPercentuale = 10;
        $this->scontoAvvolgimentoMano = 20;
        $this->scontoMultilavorazione = 0;
        $this->spedizioneH24 = 36;
        $this->spedizioneH72 = 18;
    }

    private function getSupportPrice($material) {
        switch ($material) {
            case "C001":    //Carta patinata lucida
                $price = 0.9;
                break;
            case "C002":    //Carta patinata opaca
                $price = 0.9;
                break;
            default:
                $price = 1.0;
        }
        $this->prezzoSupporto = $price;
        return $price;
    }

    private function getNumEticFoglio($base_etichetta, $altezza_etichetta, $do = 1) {
        $l_foglio = $this->larghezzaFoglio;
        $a_foglio = $this->altezzaFoglio;
        $spaziatura = $this->spaziatura;
        $maxEt = $this->maxEtichetteFoglio;

        $etichette_v1 = floor($l_foglio / ($base_etichetta + $spaziatura));
        $etichette_v2 = floor($a_foglio / ($altezza_etichetta + $spaziatura));

        $etichette_per_foglio = $etichette_v1 * $etichette_v2;

        //$etichette_per_foglio=  floor(($l_foglio)/($base_etichetta+$spaziatura)*($a_foglio))/floor(($altezza_etichetta + $spaziatura)); //calcolo il numero di etichette per ogni foglio


        if ($etichette_per_foglio > $maxEt)
            $etichette_per_foglio = $maxEt;   //nel caso siano tante considero il valore impostato come max
        if ($do)
            $this->etichetteFoglio = $etichette_per_foglio;
        if (!$etichette_per_foglio)
            $this->error = 1;
        return $etichette_per_foglio;
    }

    private function getFogliNecessari($num) {
        if (!$this->error) {
            $fogli = ceil($num / $this->etichetteFoglio);
            $this->numFogli = $fogli;
            return $fogli;
        }
    }

    private function aggiustaAvvolgimento($avv, &$b, &$h) {
        if ($this->getNumEticFoglio($b, $h, $do = 0) < $this->getNumEticFoglio($h, $b, $do = 0)) {
            $c = $b;
            $b = $h;
            $h = $c;
        }
    }

    private function prezzaLavorazioniAggiuntive($hot_stamping, $serigraphy, $relief) {
        $moltelavorazioni = 0;
        if ($hot_stamping) {
            $this->rincaro+=$this->costoAvviamentoCaldo;
            $this->prezzoSupporto+=$this->costoCaldoMq;
            $moltelavorazioni++;
        }
        if ($serigraphy) {
            $this->rincaro+=$this->costoAvviamentoSerigrafia;
            $this->prezzoSupporto+=$this->costoSerigrafiaMq;
            $moltelavorazioni++;
        }
        if ($relief) {
            $this->rincaro+=$this->costoAvviamentoRilievo;
            $this->prezzoSupporto+=$this->costoRilievoMq;
            $moltelavorazioni++;
        }
        if ($moltelavorazioni >= 2) {
            $this->rincaro-=$this->scontoMultilavorazione;
        }
    }

    private function determinaPrezzo() {
        $LarghezzaFoglio = $this->larghezzaFoglio;
        $AltezzaFoglio = $this->altezzaFoglio;
        $CostoAvviamento = $this->costoAvviamento;
        $prezzoSupporto = $this->prezzoSupporto;
        $fogliNecessari = $this->numFogli;
        $numEtichetteFoglio = $this->etichetteFoglio;
        $RincaroAntispappolo = $this->rincaroAntispappolo;
        $rincaro = $this->rincaro;
        $coefficente = ($this->prezzoMaxFoglio - $this->prezzoMinFoglio) / ($this->maxEtichetteFoglio - 1);
        $costante = $this->prezzoMinFoglio - $coefficente;
        $rincaroPercentuale = $this->rincaroPercentuale;

        $prezzo = $CostoAvviamento + ($prezzoSupporto * $fogliNecessari * $LarghezzaFoglio * $AltezzaFoglio / 1000000) + $fogliNecessari * ($coefficente * $numEtichetteFoglio + $costante) + $RincaroAntispappolo + $rincaro;
        $rincaroPercentuale*=$prezzo / 100;
        $prezzo+=$rincaroPercentuale;

        $this->label_price = $prezzo;
        return $prezzo;
    }

    private function doScontoAvvolgimento($orientation) {
        if ($orientation == 'auto') {
            $this->label_price-=$this->scontoAvvolgimentoMano * $this->label_price / 100;
        }
    }

    private function maggiorazioneSpedizioneRapida($time_delivery) {
        if ($time_delivery == 'H24') {
            $this->label_price+=$this->label_price * $this->spedizioneH24 / 100;
        }
        if ($time_delivery == 'H72') {
            $this->label_price+=$this->label_price * $this->spedizioneH72 / 100;
        }
    }

    private function getPrice() {
        return $this->label_price;
    }

    public static function calc() {
        $args = func_get_args();
        while (count($args) < 10)
            $args[] = NULL; //Parametri per la funzione
        list($label_width, $label_height, $material, $n_copy, $orientation, $time_delivery, $note, $hot_stamping, $serigraphy, $relief) = $args;
        unset($args);

        self::$tax = 22;
        self::$product_name = "Basic Label";
        self::$note = $note;

        //$orientation="1";
        //var_dump(get_defined_vars());
        /* 			ESEGUO IL CALCOLO		 */

        $error = array();

        $label = new Basic_label();  //instazio l'oggetto
        $label->getSupportPrice($material);                                             //Ottengo il prezzo del supporto (carta o altro materiale)
        $label->aggiustaAvvolgimento($orientation, $label_width, $label_height);        //viene calcolato il miglior modo per disporre le etichette 
        $label->getNumEticFoglio($label_width, $label_height);                          //calcolo il numero di etichette per posa
        $label->getFogliNecessari($n_copy);                                             //calcolo il numero di fogli da stampare
        $label->prezzaLavorazioniAggiuntive($hot_stamping, $serigraphy, $relief);       //in caso di stampa a caldo, serigrafia e rilievo c'è un aumento dei costi di avviamento e di materiale
        $label->determinaPrezzo();                                                      //esegue il calcolo per determinare il prezzo
        $label->doScontoAvvolgimento($orientation);                                     //In caso di avvolgimento manuale applica lo sconto
        $label->maggiorazioneSpedizioneRapida($time_delivery);
        self::$price = $label->getPrice();

        ob_start();
        print_r($label);
        $dataLabel = ob_get_clean();
        self::$data = get_defined_vars();
        //var_dump($label);
        if (!$label->error)
            return self::$price; //self::$price;
        else {
            echo Notification::showCode("LBB-1", NULL, true)['description'];
        }
    }

}

?>