# WebFrame
## Spiegazione generale
Si tratta del framework con cui è fatto quest il sito internet. Avevo iniziato a scriverlo autonomamente per conto mio, di recente ho aperto il progetto su Github. Usa l'interfaccia standard **Control Model View** dividento a sua volta la View in due parti "**Bone**" e "**Skin**": la prima che si occupa di dare la struttura della pagina, la seconda contiene gli elementi grafici che la compongono.
## Le classi
All'interno del progetto sono disponibili le seguenti classi:
* Captcha
* Connection
* DBConnection
* FileManage
* Notification
* Pageloader
* SettingsManage
* User

### Captcha
La classe si occupa di gestire e creare i captcha. I dati per il settaggio sono presi dalla variabile globale `$settings`.

`[...]`
