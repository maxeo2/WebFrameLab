window.addEvent('load', function() {
				new JCaption('img.caption');
			});
var nn_sliders_speed = 500; var nn_sliders_fade_in_speed = 1000; var nn_sliders_fade_out_speed = 400; var nn_sliders_linkscroll = 0; var nn_sliders_url = ''; var nn_sliders_activescroll = ''; var nn_sliders_use_hash = 1;
//<![CDATA[
			window.addEvent('domready', function() {
				document.id('chronoform_preventivo').addClass('hasValidation');
				formCheck_preventivo = new FormCheckMax('chronoform_preventivo', {
					onValidateSuccess: $empty,
					display : {
						showErrors : 0,
						errorsLocation: 1					}
				});										
			});
			
//]]>
		//<![CDATA[
			// JavaScript Document

one_mini ='05/05';
two_mini ='09/05';
week_mini ='11/05';
one_db ='2016-05-05';
two_db ='2016-05-09';
week_db ='2016-05-11';

function avvolgimento() {
if ($('applicazione_0').checked) {
$('anima').value = '76';
$('anima').setAttribute('disabled','disabled');
} else {
$('anima').removeAttribute('disabled');
}
}

function polipropilene() {
if ($('materiale').value == 'PP' || $('materiale').value == 'PB' || $('materiale').value == 'PT') {
$('sotto').value = 'NO';
$('sotto').setAttribute('disabled','disabled');
} else {
$('sotto').removeAttribute('disabled');
}
}

function mini() {
if (base>0 && altezza>0 && copie>0) {

	if ($('consegna_0').checked) {
		$('consegna_mini').innerHTML = one_mini;
		$('netto_mini').innerHTML=totone;
		$('tot_netto').value=totone;
		$('data_presunta').value=one_db;
               var totonenumber=totone.replace(/,/g,"");
		iva = Number.from(totonenumber)*0.22;
		lordo = Number.from(totonenumber)+iva;
	} else if  ($('consegna_1').checked) {
		$('consegna_mini').innerHTML = two_mini;
		$('netto_mini').innerHTML=tottwo;
		$('tot_netto').value=tottwo;
		$('data_presunta').value=two_db;
                var tottwonumber=tottwo.replace(/,/g,"");
		iva =Number.from(tottwonumber)*0.22;
		lordo = Number.from(tottwonumber)+iva;
	} else {
		$('consegna_mini').innerHTML = week_mini;
		$('netto_mini').innerHTML=totweek;
		$('tot_netto').value=totweek;
		$('data_presunta').value=week_db;
                var totweeknumber=totweek.replace(/,/g,"");
		iva = Number.from(totweeknumber)*0.22;
		lordo =Number.from(totweeknumber)+iva;
	}
	if($('caldo').value!='NN') {
		$('netto_mini').innerHTML=totcaldo;
		$('tot_netto').value=totcaldo;
                var totcaldonumber=totcaldo.replace(/,/g,"");
		iva = Number.from(totcaldonumber)*0.22;
		lordo =Number.from(totcaldonumber)+iva;
	}
	iva = iva.format({
		group: "",
		decimals: 2
	});
	lordo = lordo.format({
		group: "",
		decimals: 2
	});
	$('iva_mini').innerHTML = iva;
	$('lordo_mini').innerHTML = lordo;
}
ordine_mini();
}

function removeformat(value){
var val=value+"";
return val.replace(/,/g,"").replace(/€/g,"");
}

function ordine_mini() {
sped_ordine=  Number.from($('sped_ordine_mini').innerHTML);
sconto =  Number.from($('sconto').value);
tot_netto_ordine= tot_netto_old+Number.from(removeformat($('tot_netto').value));
tot_netto_num = Number.from(removeformat(tot_netto_ordine));
sconto_val = tot_netto_num*sconto;
iva = (tot_netto_num - sconto_val +sped_ordine)*0.22;
lordo =tot_netto_num - sconto_val +sped_ordine+iva;

sconto_val = sconto_val.format({
group: "",
decimals: 2
});
iva = iva.format({
group: "",
decimals: 2
});
lordo = lordo.format({
group: "",
decimals: 2
});
tot_netto_ordine = tot_netto_ordine.format({
group: "",
decimals: 2
});
if (tot_netto_ordine != '0.00') {
$('iva_ordine_mini').innerHTML = iva;
$('netto_ordine_mini').innerHTML = tot_netto_ordine;
$('lordo_ordine_mini').innerHTML = lordo;
if (sconto != 0) {
$('sconto_val').innerHTML = '- '+sconto_val;
}
}
if ($('ordine').value != 1) {
$('consegna_ordine_mini').innerHTML = $('consegna_mini').innerHTML;
}

}

function disabilita_date() {
if ($('ordine').value != 1) {
	$('data_secondo_container_div').style.display = 'none';
} else {
	consegna=$('consegna_ordine_mini').innerHTML;
	if (one_mini == consegna) {
		$('consegna_0').checked = true;
		$('caldo').setAttribute('disabled','disabled');
		$('consegna_1').setAttribute('disabled','disabled');
		$('consegna_2').setAttribute('disabled','disabled');
	} else if (two_mini == consegna) {
		$('consegna_1').checked = true;
		$('caldo').setAttribute('disabled','disabled');
		$('consegna_0').setAttribute('disabled','disabled');
		$('consegna_2').setAttribute('disabled','disabled');
	} else {
		$('consegna_2').checked = true;
		$('consegna_0').setAttribute('disabled','disabled');
		$('consegna_1').setAttribute('disabled','disabled');
	} 
   }
}

function logError() {
var err = 0;
if (err == '1') {
window.alert("Per poter procedere è necessario effettuare il log-in");
}
}

function formatta(position) {
test = Number.from($(position).value);
if (!test) {
test = '';
}else{
 test = test.toInt();
}
$(position).value = test;
}


function calcola() {
	base = $('base').value;
	altezza= $('altezza').value;
	copie= $('copie').value;
	
	/*alessandro inizio*/
	var orientamento = null;
	if ($('applicazione_0').checked){
		orientamento = 'a';
	} else if($('applicazione_1').checked || $('applicazione_2').checked || $('applicazione_5').checked || $('applicazione_6').checked){
		orientamento = 'v';
	}  else if($('applicazione_3').checked || $('applicazione_4').checked || $('applicazione_7').checked || $('applicazione_8').checked){
		orientamento = 'o';
}
	/*alessandro fine*/

	if ($('grafica').value == 'RF') {
		grafica=30;
	} else {
		grafica=0;
	}

        if ($('braille').value == 'SI') {
		braille=150;
	} else {
		braille=0;
	}


var checkAvv = document.getElementsByName('applicazione');
var ischeckAvv_method = false;
for( var i = 0; i < checkAvv.length; i++){
if(checkAvv[i].checked){ischeckAvv_method = true;break;}}


	if (base>0 && altezza>0 && copie>0 && ischeckAvv_method) {
		//totale = 20+0.08*copie*0.01*base*0.01*altezza;
		//area = base*altezza;
rincaro =0;
var supporto = null;
if ($('materiale').value=='PP') {
	//rincaro +=0.80;
	supporto= 'p';
}
else if ($('materiale').value=='PB') {
	//rincaro +=2.3;
	supporto= 'pb';
}
else if ($('materiale').value=='PT') {
	//rincaro +=0.80;
	supporto= 'p';
}
else if ($('materiale').value=='AS') {
	//rincaro +=0.25;
	supporto= 'as';
}
else if ($('materiale').value=='US') {
	//rincaro +=0.25;
	supporto= 'c';
}
else if ($('materiale').value=='PL') {
	//rincaro +=0.25;
	supporto= 'c';
}
else if ($('materiale').value=='PO') {
	//rincaro +=0.25;
	supporto= 'c';
}
else if ($('materiale').value=='VN') {
	//rincaro +=0.25;
	supporto= 'c';
}
else if ($('materiale').value=='VC') {
	//rincaro +=0.25;
	supporto= 'c';
}
else if ($('materiale').value=='MN') {
	//rincaro +=0.25;
	supporto= 'c';
}
else if ($('materiale').value=='MC') {
	//rincaro +=0.25;
	supporto= 'c';
}
 else {
	alert( "materiale non esistente");
}
var x = new CalcolatorePrezzi;
var totale1 = x.getPrezzo(orientamento, supporto, copie, Number(base), Number(altezza));
//alert("totale senza rincari: " + totale1);
if ($('braille').value=='SI') {
	rincaro +=1.5;
}

if ($('sotto').value=='SI') {
	rincaro +=1.5;
}

totale = totale1+ totale1*rincaro+grafica+braille+2;
if(orientamento != "a"){totale *= 1.3};
if(supporto == "pb"){totale *= 1.3};
		totweek = totale.format({
			decimals: 2
		});
		totone = 30+totale*1.2;
		totone = totone.format({
			decimals: 2
		});
		tottwo = 15+totale*1.1;
		tottwo = tottwo.format({
			decimals: 2
		});
		$('one').innerHTML = '€  ' + totone;
		$('two').innerHTML = '€  ' + tottwo;
		$('week').innerHTML = '€  ' + totweek;
	}
	
	if($('caldo').value!='NN') {
		caldoimp=100;
		caldocad=0.02;
		totcaldo = totale+copie*caldocad+caldoimp;
		totcaldo = totcaldo.format({
			decimals: 2
		});
		$('one').innerHTML = '-';
		$('two').innerHTML = '-';
		$('week').innerHTML = '€  ' + totcaldo;
		if ($('ordine').value != 1) {
		$('consegna_2').checked = true;
		$('consegna_0').setAttribute('disabled','disabled');
		$('consegna_1').setAttribute('disabled','disabled');
		}
	}else{
	if ($('ordine').value != 1) {
		$('consegna_0').removeAttribute('disabled');
		$('consegna_1').removeAttribute('disabled');
		}
	}

if ($('braille').value=='SI') {
$('one').innerHTML = '-';
$('two').innerHTML = '-';
$('consegna_2').checked = true;
$('consegna_0').setAttribute('disabled','disabled');
$('consegna_1').setAttribute('disabled','disabled');
}else{
$('consegna_0').removeAttribute('disabled');
$('consegna_1').removeAttribute('disabled');
}
if ($('sotto').value=='SI') {
$('one').innerHTML = '-';
$('two').innerHTML = '-';
$('consegna_2').checked = true;
$('consegna_0').setAttribute('disabled','disabled');
$('consegna_1').setAttribute('disabled','disabled');
}else{
$('consegna_0').removeAttribute('disabled');
$('consegna_1').removeAttribute('disabled');
}
if ($('materiale').value=='PB') {
$('one').innerHTML = '-';
$('two').innerHTML = '-';
$('consegna_2').checked = true;
$('consegna_0').setAttribute('disabled','disabled');
$('consegna_1').setAttribute('disabled','disabled');
}else{
$('consegna_0').removeAttribute('disabled');
$('consegna_1').removeAttribute('disabled');
}
	mini();
}





window.addEvent('domready', function() {
tot_netto_old=  Number.from($('netto_ordine_mini').innerHTML) ;
calcola();
$('base').addEvent('keyup' , function(event){
formatta('base');calcola();
});
$('altezza').addEvent('keyup' , function(event){
formatta('altezza');calcola();
});
$('materiale').addEvent('change' , function(event){
calcola();
});
$('caldo').addEvent('change' , function(event){
calcola();
});
$('copie').addEvent('keyup' , function(event){
formatta('copie');calcola();
});
$('braille').addEvent('change' , function(event){
formatta('copie');calcola();
});
$('sotto').addEvent('change' , function(event){
formatta('copie');calcola();
});
$('grafica').addEvent('change' , function(event){
calcola();
});
$('materiale').addEvent('change' , function(event) {
polipropilene();
});
$('consegna_0').addEvent('click' , function(event) {
mini();
});
$('consegna_1').addEvent('click' , function(event) {
mini();
});
$('consegna_2').addEvent('click' , function(event) {
mini();
});
$('applicazione_0').addEvent('click' , function(event) {
calcola();
});
$('applicazione_1').addEvent('click' , function(event) {
calcola();
});
$('applicazione_2').addEvent('click' , function(event) {
calcola();
});
$('applicazione_3').addEvent('click' , function(event) {
calcola();
});
$('applicazione_4').addEvent('click' , function(event) {
calcola();
});
$('applicazione_5').addEvent('click' , function(event) {
calcola();
});
$('applicazione_6').addEvent('click' , function(event) {
calcola();
});
$('applicazione_7').addEvent('click' , function(event) {
calcola();
});
$('applicazione_8').addEvent('click' , function(event) {
calcola();
});
$('applicazione_0').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_1').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_2').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_3').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_4').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_5').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_6').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_7').addEvent('click' , function(event) {
avvolgimento();
});
$('applicazione_8').addEvent('click' , function(event) {
avvolgimento();
});
//posiziona_lat();
disabilita_date();
avvolgimento();
logError();
});


function CalcolatorePrezzi() {
     var Verticale = "v";
     var Orizzontale = "o";
     var Automatico = "a";
     var Carta = "c";
     var Poliestere = "p";
     var PoliestereTraspBianco = "pb";
     var AntiSpappolo = "as";
     var CostoPoliestere = 2.0;
     var CostoAntiSpappolo = 1.4;
     var CostoPoliestereTraspBiancoAvviamento = 140;
     var CostoCarta = 1;
     var CostoAvviamento = 15.0;
     var PrezzoMin = 21.0;
     var Spaziatura = 5;
     var LarghezzaFoglio = 290;
     var AltezzaFoglio = 1200;
     var PrezzoMinFoglio = 1.0;
     var PrezzoMaxFoglio = 6.0;
     var MinEtichetteFoglio  = 1;
     var MaxEtichetteFoglio = 800;
     var CostoAvviamentoBraille = 250;
     var CostoAvviamentoCaldo = 120;
     var CostoAvviamentoSotto = 100;
     var CostoBrailleMq = 0.1;
     var CostoCaldoMq = 0.6;
     var CostoSottoMq = 0.025;
     var RincaroAntispappolo = 0;
     

     this.getPrezzo = function (orientamento, supporto, num, base, altezza, braille, caldo, sotto){
        var numEtichetteFoglio = -1;
        var b = -1;
        var h = -1;
        if(orientamento == Automatico){
            var numOrizzontale = getNumEticFoglio(base, altezza);
            var numVerticale = getNumEticFoglio(altezza, base);
            if (numOrizzontale >= numVerticale){
                numEtichetteFoglio = numOrizzontale;
                orientamento = Orizzontale;
            }
            else{
                numEtichetteFoglio = numVerticale;
                orientamento = Verticale;
            }
        }
        else if(orientamento == Orizzontale){
            numEtichetteFoglio = getNumEticFoglio(base, altezza);    
        }
        else if(orientamento == Verticale){
            numEtichetteFoglio = getNumEticFoglio(altezza, base);    
        }
        else{
            //errore
            alert( "orientamento stampa errato: deve essere "  + "" + Orizzontale + "" +  " per orizzontale, " + "" + Verticale + "" + " per verticale");
            return -1;
        }
        
        if(orientamento == Orizzontale){
            b = base;
            h = altezza;

        } else if (orientamento == Verticale){
            b = altezza;
            h = base;
            
        } else{
            //errore
            alert( "orientamento stampa errato: deve essere "  + "" + Orizzontale + "" +  " per orizzontale, " + "" + Verticale + "" + " per verticale");
            return -1;
        }
            
        var fogliNecessari = Math.ceil(num / numEtichetteFoglio);
        var prezzo = getPrezzoPerSupporto(supporto, fogliNecessari, numEtichetteFoglio, b, h, braille, caldo, sotto);
        return prezzo;
    };

    function getPrezzoPerSupporto (supporto, fogliNecessari, numEtichetteFoglio, b, h, braille, caldo, sotto){
        var prezzo = -1;
        var prezzoSupporto = -1;
		var rincaro = 0;
        if(supporto == Carta){
            prezzoSupporto = CostoCarta;
        }
        else if(supporto == Poliestere){
            prezzoSupporto = CostoPoliestere;
        }
	    else if(supporto == AntiSpappolo){
            prezzoSupporto = CostoAntiSpappolo;
            RincaroAntispappolo = 120;        
        }
	    else if(supporto == PoliestereTraspBianco){
	    prezzoSupporto = CostoPoliestere;
            CostoAvviamento = CostoAvviamento + CostoPoliestereTraspBiancoAvviamento;
        }
        else{
            //errore
            alert( "supporto stampa errato: deve essere " + "" + Carta + "" + " per carta, " + "" + Poliestere  + "" + " per poliestere");
            return -1;
        }
      
        var numCarat = 0;
        if(braille){
            rincaro += CostoAvviamentoBraille;
            numCarat+=1;
            prezzoSupporto+=CostoBrailleMq;
        }
        if(caldo){
            rincaro += CostoAvviamentoCaldo;
            numCarat+=1;
            prezzoSupporto+=CostoCaldoMq;
        }
        if(sotto){
            rincaro += CostoAvviamentoSotto;
            numCarat+=1;
            prezzoSupporto+=CostoSottoMq;
        }
        if(numCarat >= 2){
            rincaro -= 40;
        }
        
        
        var coefficiente = (PrezzoMaxFoglio - PrezzoMinFoglio)/(MaxEtichetteFoglio  - MinEtichetteFoglio);
        var costante = PrezzoMinFoglio - coefficiente;
        prezzo =  CostoAvviamento + (prezzoSupporto * fogliNecessari * LarghezzaFoglio * AltezzaFoglio / 1000000) + fogliNecessari * (coefficiente * numEtichetteFoglio + costante) + RincaroAntispappolo;
        prezzo += rincaro;
        if(prezzo < PrezzoMin){
            prezzo = PrezzoMin;
        }
        return prezzo;
    }

    function getNumEticFoglio (b, h){
        var numEtichette = Math.floor((LarghezzaFoglio + Spaziatura)/(b + Spaziatura)) * Math.floor((AltezzaFoglio + Spaziatura)/(h + Spaziatura));
        if (numEtichette > MaxEtichetteFoglio){
            numEtichette = MaxEtichetteFoglio;
        }
        return numEtichette;
    }    
}		//]]>
		
var ubase="http://www.stampaetichetteonline.com/";
		//<![CDATA[
			function altezzaOrdineMini() {
if ($('sconto').value !=0) {
$('value_order_container_div').style.height = '110px';
}
}

window.addEvent('domready', function() {

altezzaOrdineMini();
});		//]]>
		
function keepAlive() {	var myAjax = new Request({method: "get", url: "index.php"}).send();} window.addEvent("domready", function(){ keepAlive.periodical(1740000); });