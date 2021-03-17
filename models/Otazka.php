<?php

class Otazka
{
    private $id;
    private $poradi;
    private $druh;
    private $text;
    private $leva;
    private $prava;
    private $skolnirok;

    public function __construct($id, $druh, $text, $poradi)
    {
        $this->id = $id;
        $this->druh = $druh;
        $this->poradi = $poradi;
        $this->skolnirok = Config::getValueFromConfig("skolnirok_id");
        if ($this->druh == "otevřená") {
            $this->text = $text;
        } else if ($this->druh == "výběrová") {
            list($this->text, $this->leva, $this->prava) = explode(";", $text, 3);
        }
    }

    public function vypisOtazku()
    {
        echo '<div class="card otazka mb-4" id="div' . $this->id . '">';
        if ($this->druh == "otevřená") {
            echo '<div class="card-header">';
            echo '<h4 class="card-title my-auto">' . $this->poradi . '. '  . $this->text . '</h4>';
            echo '</div>';
            echo '<div class="container mx-auto card-body">';
            echo '<textarea class="form-control" id="text' . $this->id . '" name="' . $this->id . '"></textarea>';
            echo '</div>';
        } else if ($this->druh == "výběrová") {
?>
            <div class="card-header">
                <h4 class="card-title my-auto"><?php echo $this->poradi . '. ' . $this->text; ?></h4>
            </div>
            <div class="card-body row ml-0 mr-0 textFix">
                <div class=""><?php echo $this->leva; ?></div>
                <div class="radio-wrapper text-center btn-group">
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="1-<?php echo $this->id; ?>">
                            1
                            <input type="radio" id="1-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="1" required>
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="2-<?php echo $this->id; ?>">
                            2
                            <input type="radio" id="2-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="2">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="3-<?php echo $this->id; ?>">
                            3
                            <input type="radio" id="3-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="3">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="4-<?php echo $this->id; ?>">
                            4
                            <input type="radio" id="4-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="4">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="5-<?php echo $this->id; ?>">
                            5
                            <input type="radio" id="5-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="5">
                        </label>
                    </div>
                </div>
                <div class=""><?php echo $this->prava; ?></div>
            </div>

<?php
        }
        echo '</div>';
    }

    public static function vypisOtazkyStudent($predmet, $ucitel, array $otazky)
    {
        echo '<form class="form mx-auto my-4 pb-2" action="/p/' . $ucitel . '/' . $predmet . '/submit" method="post">';
        foreach ($otazky as $otazka) {
            $o = new Otazka($otazka["id"], $otazka["druh"], $otazka["otazka"], $otazka["poradi"]);
            $o->vypisOtazku();
        }
        echo '<input class="btn btn-secondary" type="submit" name="Odeslat" value="Submit">';
        echo '</form>';
    }

    public static function vypisOtazkyUcitel($predmet, $trida, $skupina, array $otazky)
    {
        echo '<form class="form mx-auto my-4 pb-2" action="/t/' . $trida . '/' . $predmet . '/' . $skupina . '/submit" method="post">';
        foreach ($otazky as $otazka) {
            $o = new Otazka($otazka["id"], $otazka["druh"], $otazka["otazka"], $otazka["poradi"]);
            $o->vypisOtazku();
        }
        echo '<input class="btn btn-secondary" type="submit" name="Odeslat" value="Submit">';
        echo '</form>';
    }

    public static function vratOtazkyProStudenty()
    {
        $otazky = Databaze::dotaz("SELECT * FROM studenti_otazky WHERE skolnirok LIKE ? order by poradi asc", array(Config::getValueFromConfig("skolnirok_id")));
        return $otazky;
    }
    public static function vratOtazkyProUcitele()
    {
        $otazky = Databaze::dotaz("SELECT * FROM ucitele_otazky WHERE skolnirok LIKE ? order by poradi asc", array(Config::getValueFromConfig("skolnirok_id")));
        return $otazky;
    }

    public static function aktualizujOtazkuStudenta($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("UPDATE studenti_otazky SET otazka = ?, druh = ? WHERE skolnirok like ? and id like ?", array($otazka, $druh, $skolnirok, $id));
    }

    public static function aktualizujOtazkuUcitele($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("UPDATE ucitele_otazky SET otazka = ?, druh = ? WHERE skolnirok like ? and id like ?", array($otazka, $druh, $skolnirok, $id));
    }

    public static function pridejOtazkuStudentovi($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("INSERT INTO studenti_otazky(poradi, otazka, druh, skolnirok) VALUES(?, ?, ?, ?)", array($id, $otazka, $druh, $skolnirok));
    }
    public static function pridejOtazkuUciteli($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("INSERT INTO ucitele_otazky(poradi, otazka, druh, skolnirok) VALUES(?, ?, ?, ?)", array($id, $otazka, $druh, $skolnirok));
    }

    public static function prenesOtazky($staryRok, $novyRok) {
        Databaze::dotaz("INSERT INTO studenti_otazky(poradi, otazka, druh, skolnirok)
                         SELECT poradi, otazka, druh, ? as skolnirok FROM studenti_otazky WHERE skolnirok = ?", array($novyRok, $staryRok));

        Databaze::dotaz("INSERT INTO ucitele_otazky(poradi, otazka, druh, skolnirok)
                         SELECT poradi, otazka, druh, ? as skolnirok FROM ucitele_otazky WHERE skolnirok = ?", array($novyRok, $staryRok));
    }

    public static function importDefaultOtazky() {
        Databaze::dotaz("INSERT INTO studenti_otazky(poradi, skolnirok, otazka, druh) VALUES
            (1, 1, 'ATMOSFÉRA;v jeho/jejích hodinách je dobrá atmosféra, cítím se vždy velmi dobře;většinou se necítím dobře', 'výběrová'),
            (2, 1, 'POBÍDKA K PRÁCI;umí přimět třídu k práci příjemným způsobem;většinou se mu/ji nepodaří přimět třídu k práci', 'výběrová'),
            (3, 1, 'PŘÍSTUP KE TŘÍDĚ;má pro žáky pochopení, umí se vcítit do pocitu žáků;je lhostejný/á k žákům', 'výběrová'),
            (4, 1, 'ZÁJEM O PŘEDMĚT;předmět mne velice zajímá, problematice se věnuji i mimoškolně;nemám rád tento předmět', 'výběrová'),
            (5, 1, 'REAKCE NA KRITIKU;je schopen/schopna přijmout kritiku svých hodin;nepřipouští jakoukoli kritiku svých hodin', 'výběrová'),
            (6, 1, 'OZNÁMKUJTE SVÉ ZNALOSTI;jednička jako ve škole;pětka jako ve škole', 'výběrová'),
            (7, 1, 'KONTROLA PRÁCE ŽÁKŮ;pravidelně kontroluje naši práci;nikdy nekontroluje naši práci', 'výběrová'),
            (8, 1, 'TRPĚLIVOST;je trpělivý/á a nebojíme se jej/jí požádat o opakovaný výklad učiva;je netrpělivý/á, nerad/a znovu opakuje již probrané učivo', 'výběrová'),
            (9, 1, 'VÝSLEDEK;v jeho/jejích hodinách se toho hodně naučím;v jeho/jejích hodinách se toho naučím málo', 'výběrová'),
            (10, 1, 'PLÁNOVÁNÍ;vždy má hodinu přesně připravenou a vždy víme, co máme dělat;v hodinách často improvizuje, výuka je zmatená', 'výběrová'),
            (11, 1, 'PODPORA SAMOSTATNÉHO UVAŽOVÁNÍ;nechává nás, abychom všechno, co můžeme. objevili sami;nikdy nám nezadává takové úkoly, při nichž musíme samostatně objevovat', 'výběrová'),
            (12, 1, 'ELÁN;pracuje s velkým elánem;pracuje znuděně a jako by byl/a bez života', 'výběrová'),
            (13, 1, 'VÝKLAD;vysvětluje látku jasně, stručně, drží se tématu, umí vysvětlovat;jeho/její výklad často nedává žádný smysl', 'výběrová'),
            (14, 1, 'SPRAVEDLIVOST;hodnotí spravedlivě, přistupuje ke všem žákům stejně;nehodnotí spravedlivě, některým žákům nadržuje a jiné nemá v oblibě', 'výběrová'),
            (15, 1, 'POMOC;bedlivě sleduje, zda někdo nemá problémy s jeho/jejím předmětem, a je vždy připraven/a pomoci;nezajímá ho/jí, že má někdo problémy s jeho/jejím předmětem', 'výběrová'),
            (16, 1, 'PRŮBĚH HODINY;hospitovaná hodina proběhla naprosto standardní formou;hospitovaná hodina byla naprosto odlišná od standardní vyučovací hodiny', 'výběrová'),
            (17, 1, 'Zde nám můžete stručně sdělit svůj názor. Akceptujeme pouze slušně vyjádřené podněty a připomínky.', 'otevřená');");

        Databaze::dotaz("INSERT INTO ucitele_otazky(poradi,skolnirok, otazka, druh) VALUES
            (1,1, 'ATMOSFÉRA;v této třídě se cítím vždy velmi dobře;v této třídě se cítím vždy špatně', 'výběrová'),
            (2,1, 'PŘÍSTUP K PRÁCI;žáci jsou aktivní, soustředění a samostatní, pracují systematicky a s elánem;žáci jsou pasivní, je velmi těžké přimět je k práci, jen jednotlivci pracují', 'výběrová'),
            (3,1, 'PŘÍSTUP K UČITELI;žáci jsou zdvořilí, otevření a přátelští;žáci zaujímají odmítavý postoj, někdy se u ních objeví projevy nepřátelství', 'výběrová'),
            (4,1, 'ZÁJEM O PŘEDMĚT;žáci se aktivně snaží rozšiřovat si znalosti v daném oboru, účastní se soutěží, sledují i odbornou literaturu v daném předmětu apod.;žáci většinou nejsou připraveni, nejeví žádný zájem o můj předmět', 'výběrová'),
            (5,1, 'REAKCE NA KRITIKU;žáci jsou schopni přijmout kritiku a snaží se o nápravu;žáci odmítají přijmout jakoukoli kritiku', 'výběrová'),
            (6,1, 'OZNÁMKUJTE ZNALOSTI TŘÍDY;jednička jako ve škole;pětka jako ve škole', 'výběrová'),
            (7,1, 'KONTROLA PRÁCE ŽÁKŮ;pravidelně kontroluji práci žáků; nikdy nekontroluji práci žáků', 'výběrová'),
            (8,1, 'TRPĚLIVOST;rád vysvětlím stejné učivo opakovaně;opakování je ztráta času, někteří žáci nemají předpoklady ke studiu na naší škole', 'výběrová'),
            (9,1, 'VÝSLEDEK;předpokládám, že se všichni žáci hodně naučí přímo v hodinách;předpokládám, že se všichni žáci musí především hodně učit doma, aby zvládli látku mých hodin', 'výběrová'),
            (10,1, 'PLÁNOVÁNÍ;hodinu mám předem přesně rozvrženou na časové úseky s daným obsahem práce, pravidelně kontroluji soulad s tématickými plány;hodinu neplánuji, dávám přednost improvizaci, tématické plány nejsou zavazující', 'výběrová'),
            (11,1, 'PODPORA SAMOSTATNÉHO UVAŽOVÁNÍ;nechávám na žácích, aby všechno, co můžou objevili sami;nikdy nezadávám takové úkoly, naši žáci neumí nic samostatně objevit', 'výběrová'),
            (12,1, 'ZDE NÁM MŮŽETE SDĚLIT NÁZOR NA PŘÍSLUŠNOU TŘÍDU/SKUPINU ŽÁKŮ', 'otevřená');");

        //$query = str_replace(array("\n", "\r"), '', file_get_contents('default_questions.sql')); //NEVIM NECHAPU
        //print_r($query);
        // $query = file_get_contents('default_questions.sql');
        // Databaze::dotaz($query);

        //pokus ze souboru, ale php neco dela a ja nevim co a nefunguje to...
    }
}
