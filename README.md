# Odorik antispam

Tato malá webová aplikace slouží jako ochrana před spamem přes IVR menu řízené webem na Odoriku.

## Jak to funguje?

Po přepojení na nastavené IVR menu řízené webem se ozve výzva k zadání n náhodně vygenerovaných číslic číslic (paramter "length"). V případě, že volající zadá číslice správně, vykoná se příkaz předaný jako "success", v opačném případě se vykoná příkaz předaný jako "failure".

### Jak to funguje podrobně?

* Po přepojení na nastavné IVR menu řízené webem Odorik zašle požadavek zahrnující ID hovoru na URL aplikace, tedy soubor _index.php_.
* Ten ověří přítomnost parametrů zadaných uživatelem a Odorikem, vygeneruje číselný kód a uloží jej společně s hashovaným ID hovoru do souboru _data.json_.
* Následně Odoriku příkaže přehrání úvodní hlášky požadující po volajícím zadání číslic, přehrání vygenerovaných číslic a nakonec vyžádá zadání DTMF znaků podle počtu generovaných číslic.
* Jakmile volající zadá DTMF znaky, Odorik je předá společně s dalšími informacemi na URL souboru _verify.php_.
* Ten v souboru _data.json_ vyhledá kód dle hashovaného ID, odebere jej ze souboru a porovná jej se zadanými DTMF znaky.
* V případě úspěchu vrací Odoriku příkaz předaný jako "success", v opačném případě vrací příkaz předaný jako "failure".

## Jak to nastavím?

V administraci vašeho Odorik účtu vytvořte nové IVR menu řízené webem. Jako URL zadejte buďto produkční adresu této aplikace: **(doplnit)** a nebo adresu na místo, kde aplikaci hostujete sami. Počet očekávaných DTMF znaků zvolte 0.

V URL se nachází 3 povinné GET parametry a 1 nepovinný.

| Název     | Význam                                                    | Povinný |
|-----------|-----------------------------------------------------------|---------|
| length    | Počet generovaných cifer                                  | Ano     |
| success   | Příkaz, který se vykoná po zadání správných cifer.        | Ano     |
| failure   | Příkaz, který se vykoná po zadání špatných cifer.         | Ano     |
| no_prompt | Pokud je zadán, nepřehraje se úvodní hláška, jen číslice. | Ne      |

**Dvojtečku v příkazu nahraďte podtržítkem.** Více info o příkazech: [Odorik wiki](http://www.odorik.cz/w/ivr:vzdalene_rizeni_pres_web).

## Jak aplikaci můžu hostovat?

Aplikaci je možno spustit na libovolném webovém hostingu podporujícím PHP. Je žádoucí, nicméně ne nutné, aby zvenčí byla přístupná pouze složka _public_. (Druhou možností je pomocí souboru _.htaccess_ zakázat zvenčí přístup k souboru _data.json_)

## Osobní údaje v mnou hostované aplikaci

Jak bylo zmíněno výše, je možno využít aplikaci hostovanou mnou na adrese **(doplnit)**. Je zabezpečena tak, aby soubor _data.json_, který obsahuje informace o generovaném kódu nebyl veřejně přístupný.

Soubor _data.json_ obsahuje dvě informace: [hashované](https://cs.wikipedia.org/wiki/Hašovací_funkce) ID hovoru a generovaný kód. Díky tomu, že je ID hovoru [hashované](https://cs.wikipedia.org/wiki/Hašovací_funkce) může aplikace rozpoznat, že se jedná o tentýž hovor, nicméně **není možno z uložených dat zjistit z jakého telefonního čísla hovor přichází**. Pokud navíc volající nezavěsí před přepojením na výzvu zadání číslic, **bude před vykonáním příkazu success jeho záznam odstraněn**.
