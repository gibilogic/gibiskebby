gibiskebby
==========

Skebby integration library for Joomla

**Descrizione**

"GiBi Skebby" è una libreria di integrazione per Joomla del servizio di invio SMS Skebby.

La libreria è realizzata da GiBiLogic e rilasciata gratuitamente sotto licenza GPL v2 o successive.

L'obiettivo principale è semplificare ad altri sviluppatori l'utilizzo di Skebby, fornendo dei semplici metodi di invio che non richiedono l'approfondimento delle API di Skebby.

**Componente**

In aggiunta alla libreria viene fornito un *componente* per Joomla che ha due scopi:

* gestire alcune opzioni avanzate di configurazione della libreria (es. modalità di test)
* permettere di inviare singoli SMS, sia come test sia nel caso in cui sia effettivamente necessario l'invio

**Installazione**

In Joomla 2.5, sia la libreria che il componente sono installabili come qualsiasi altra estensione per Joomla.

In Joomla 1.5, il componente può essere installato normalmente mentre la libreria deve essere caricata a mano. In particolare, il file gibiskebby.php deve risiedere in

/libraries/gibiskebby/

**Configurazione**

Una volta installato il componente, aprire le "Preferenze" / "Opzioni" per inserire i dati di configurazione necessari.

A quel punto è già possibile effettuare un invio dal componente stesso.

**Utilizzo**

Da qualsiasi altra estensione, è possibile usare la libreria come riportato nell'esempio seguente.
E' un esempio molto generico, che dovrebbe tuttavia essere adatto alla maggior parte delle situazioni

// Importa la libreria
jimport('gibiskebby.gibiskebby');

// Costruisce l'oggetto
$skebby = new GibiSkebby(
  $skebby_username,
  $skebby_password,
  $sender_number,
  $sender_name
);

// Qui è possibile usare altri metodi della libreria per effettuare configurazioni aggiuntive
// ...

$skebby->sendSms(
  $phone_number,
  $sms_text,
  $skebby_method
);

**Supporto**

Al momento non ci è possibile fornire alcuna forma di supporto.

Se avete bisogno di personalizzazioni avanzate e/o di supporto commerciale, potete contattarci all'indirizzo info@gibilogic.com.

