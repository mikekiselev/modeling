<html>
<head>

    <title><?= $_SESSION['user']; ?></title>
    <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT" />
    <meta http-equiv="Pragma" content="no-cache" />
</head>
<body>
<h1>Моделирование исходящих вызовов</h1>
<p></p>
<form action="/calls/output.php" method="POST">
    <table border="1" >
        <tr>
            <td>
                кол-во операторов
            </td>
            <td>
                <input type="text" name="klv" value="30">
            </td>
        </tr>

        <tr>
            <td>
                средняя длительность разговора в секундах
            </td>
            <td>
                <input type="text" name="dlt" value="60">

            </td>

        </tr>

        <tr>
            <td>
                среднее количество вызовов сделанных оператором за 1 час
            </td>
            <td>
                <input type="text" name="viz" value="30">


            </td>
        </tr>

        <tr>
            <td>
                вероятность успешного звонка (от 0 до 1)
            </td>
            <td>
                <input type="text" name="ver" value="0.7">

            </td>

        </tr>

        <tr>
            <td>
                <input type=submit value="Вывести">
            </td>
            <td>
                <input type="reset" name="but1" value="reset">
            </td>
        </tr>
    </table>




</form>
<br>
<?php

if(empty($_POST))
{

    return;
}

/*
$op_kolvo=30; // кол-во операторов
$rzg=60;	// средняя длительность разговора в секундах
$kvo=28;   // среднее количество вызовов сделанных оператором за 1 час
$ver=0.7; // вероятность успешного звонка - 70%
*/

$op_kolvo=$_POST['klv']; // кол-во операторов
$rzg=$_POST['dlt'];	// средняя длительность разговора в секундах
$kvo=$_POST['viz'];;   // среднее количество вызовов сделанных оператором за 1 час
$ver=$_POST['ver'];; // вероятность успешного звонка - 70%

$kon=10; // кол-во прогонов

$SUM=0; // всего вызовов за 1 час
$SUM1=0; // всего успешных вызовов за 1 час
$DLIT=0; // общая длительность

$op=array();
for($j=0;$j<$op_kolvo;$j++)
    $op[$j] = new operator($rzg, $kvo, $ver);

for($i=0;$i<$kon;$i++)
{
    $hour=0;

    for($j=0;$j<$op_kolvo;$j++)
        $op[$j]->init();

    while($hour<3600)
    {

        for($j=0;$j<$op_kolvo;$j++)
            $op[$j]->time();

        $hour++;
    }
    for($j=0;$j<$op_kolvo;$j++)
    {
        $SUM+=$op[$j]->getAll_count();
        $SUM1+=$op[$j]->getUsp_count();
        $DLIT+=$op[$j]->getDlit();
    }
}


echo '<br>Кол-во операторов -'.$op_kolvo;
echo '<br>средняя длительность разговора -'.$rzg;
echo '<br>количество вызовов сделанных оператором за 1 час -'.$kvo;
echo '<br>вероятность успешного звонка -'.$ver;

echo '<br>';
echo '<br>РЕЗУЛЬТАТ сделано звонков - '.$SUM/$kon;
echo '<br>РЕЗУЛЬТАТ сделано успешных звонков - '.$SUM1/$kon;
echo '<br>РЕЗУЛЬТАТ средняя длительность  - '.$DLIT/($kon);
echo '<br>';
echo '<br>РЕЗУЛЬТАТ сделано звонков 1 оператора - '.$SUM/($kon*$op_kolvo);
echo '<br>РЕЗУЛЬТАТ сделано успешных звонков 1 оператора - '.$SUM1/($kon*$op_kolvo);
echo '<br>РЕЗУЛЬТАТ средняя длительность 1 оператора - '.$DLIT/($kon*$op_kolvo);
echo '<br>РЕЗУЛЬТАТ средняя длительность 1 разговора 1 оператора - '.$DLIT/$SUM1;

class operator
{
    private $rzg;
    private $kvo;
    private $ver;

    private $all_count; // всего вызовов
    private $usp_count; // всего успешных вызовов;
    private $dlit; //  сколько говорили

    private $status; // занят или нет оператор в данный момент

    public function __construct($rzg, $kvo, $ver)
    {
        $this->rzg=$rzg;
        $this->kvo=$kvo;
        $this->ver=$ver;
    }

    public function __destruct()
    {

    }

    public function init()
    {
        $this->all_count=0;
        $this->usp_count=0;
        $this->status=0;
        $this->dlit=0;
    }

    public function time()
    {
        ($this->status>0)? $this->status-- : $this->make_call();
    }



    /**
     * @return the $all_count
     */
    public function getAll_count() {
        return $this->all_count;
    }

    /**
     * @return the $usp_count
     */
    public function getUsp_count() {
        return $this->usp_count;
    }

    /**
     * @return the $dlit
     */
    public function getDlit() {
        return $this->dlit;
    }

    private function make_call()
    {
        if($this->dlit > 2400)
            return;
        if($this->usp_count > $this->kvo)
            return;
        $this->all_count++;

        $a=rand(0,1);

        if($a >$this->ver)
            return; // вызов неуспешен

        $rd=rand($this->rzg-20, $this->rzg+60); // длительность успешного вызова
        $this->usp_count++;
        $this->status=$rd;
        $this->dlit+=$rd;
    }
}
?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    var yaParams = {/*Здесь параметры визита*/};
</script>

<div style="display:none;"><script type="text/javascript">
        (function(w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter10062829 = new Ya.Metrika({id:10062829, enableAll: true, trackHash:true,params:window.yaParams||{ }});
                }
                catch(e) { }
            });
        })(window, "yandex_metrika_callbacks");
    </script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/10062829" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-25987049-1']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_trackPageLoadTime']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
</body>
</html>
