<?php

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');
session_start();

class Zone{
    const NORTH = 0;
    const EAST = 1;
    const SOUTH = 2;
    const WEST = 3;
    
    public static function getZone($num){
        switch($num){
            case self::NORTH :
                return '前';
                break;
            case self::EAST :
                return '右';
                break;
            case self::SOUTH :
                return '後ろ';
                break;
            case self::WEST :
                return '左';
                break;
        }
    }
}

class Maze{
    protected $width;
    protected $height;
    protected $maze;
    protected $startPoint;
    
    public function __construct($width, $height){
        if(($width % 2) === 0) $width++;
        if(($height % 2) === 0) $height++;
        $this -> width = $width;
        $this -> height = $height;
        $this -> maze = array();
        $this -> startPoint = array();
    }
    
    public function getMaze(){
        return $this -> maze;
    }
    
    public function createMaze(){
        for($x = 0; $x < $this -> width; $x++){
            for($y = 0; $y < $this -> height; $y++){
                if($x === 0 || $y === 0 || $x === $this -> width -1 || $y === $this -> height -1){
                    $this -> maze[$x][$y] = 'Way';
                }else{
                    $this -> maze[$x][$y] = 'Wall';
                }
            }
        }
        
        $this -> Dig(1,1);
        
        for($x = 0; $x < $this -> width; $x++){
            for($y = 0; $y < $this -> height; $y++){
                if($x === 0 || $y === 0 || $x === $this -> width -1 || $y === $this -> height -1){
                    $this -> maze[$x][$y] = 'Wall';
                }
            }
        }
    }
    
    public function Dig($x, $y){
        
        while(true){
            $direction = array();
            if($this -> maze[$x][$y-1] === 'Wall' && $this -> maze[$x][$y-2] === 'Wall'){
                $direction[] = Zone::NORTH;
            }
            if($this -> maze[$x][$y+1] === 'Wall' && $this -> maze[$x][$y+2] === 'Wall'){
                $direction[] = Zone::SOUTH;
            }
            if($this -> maze[$x-1][$y] === 'Wall' && $this -> maze[$x-2][$y] === 'Wall'){
                $direction[] = Zone::WEST;
            }
            if($this -> maze[$x+1][$y] === 'Wall' && $this -> maze[$x+2][$y] === 'Wall'){
                $direction[] = Zone::EAST;
            }

            if(empty($direction)) break;
            $this -> SetWay($x, $y);
            $direction = $direction[mt_rand(0, count($direction)-1)];
            switch($direction){
                case Zone::NORTH :
                    $this -> SetWay($x, --$y);
                    $this -> SetWay($x, --$y);
                break;
                case Zone::SOUTH :
                    $this -> SetWay($x, ++$y);
                    $this -> SetWay($x, ++$y);
                break;
                case Zone::WEST :
                    $this -> SetWay(--$x, $y);
                    $this -> SetWay(--$x, $y);
                break;
                case Zone::EAST :
                    $this -> SetWay(++$x, $y);
                    $this -> SetWay(++$x, $y);
                break;
            }
        }
        $cell = $this -> GetStartPoint();
        if(!empty($cell)) $this -> Dig($cell['X'], $cell['Y']);
    }
    
    public function SetWay($x, $y){
        $this -> maze[$x][$y] = 'Way';
        if($x % 2 === 1 && $y % 2 === 1){
            $this -> startPoint[] = ['X' => $x, 'Y' => $y];
        }
    }
    
    public function GetStartPoint(){
        if(empty($this -> startPoint)) return null;
        $index = mt_rand(0, count($this -> startPoint)-1);
        $cell = $this -> startPoint[$index];
        unset($this -> startPoint[$index]);
        $this -> startPoint = array_values($this -> startPoint);
        return $cell;
    }
    
}

class Map extends Maze{
    protected $start;
    protected $nextFloor;
    protected $now;
    protected $face;
    protected $moved;

    public function __construct($width, $height){
        parent::__construct($width, $height);
        $this -> start = array();
        $this -> nextFloor = array();
        $this -> now = array();
        $this -> face = 0;
        $this -> moved = array();
    }
    public function getStart(){
        return $this -> start;
    }
    public function getNextFloor(){
        return $this -> nextFloor;
    }
    public function getFace(){
        return $this -> face;
    }

    public function setStartAndNextFloor(){
        while(true){
            $x = mt_rand(1, $this -> width -1);
            $y = mt_rand(1, $this -> height -1);
            if($this -> maze[$x][$y] === 'Way'){
                $this -> start = ['X' => $x, 'Y' => $y];
                $this -> setNow($this -> start);
                break;
            }
        }
        while(true){
            $x = mt_rand(1, $this -> width -1);
            $y = mt_rand(1, $this -> height -1);
            if($this -> maze[$x][$y] === 'Way' && ($x !== $this -> start['X'] || $y !== $this -> start['Y'])){
                $this -> nextFloor = ['X' => $x, 'Y' => $y];
                break;
            }
        }
    }

    public function setNow($num){
        $this -> now = $num;
    }
    public function getNow(){
        return $this -> now;
    }
    public function clearMoved(){
        $this -> moved = array();
    }
    public function getMoved(){
        return $this -> moved;
    }

    public function MovableDirection(){
        $x = $this -> now['X'];
        $y = $this -> now['Y'];
        $direction = array();

        if($this -> maze[$x][$y-1] === 'Way'){
            $direction[] = Zone::NORTH;
        }
        if($this -> maze[$x+1][$y] === 'Way'){
            $direction[] = Zone::EAST;
        }
        if($this -> maze[$x][$y+1] === 'Way'){
            $direction[] = Zone::SOUTH;
        }
        if($this -> maze[$x-1][$y] === 'Way'){
            $direction[] = Zone::WEST;
        }
        return $direction;
    }


    public function MovedPosition($direction){
        $x = $this -> now['X'];
        $y = $this -> now['Y'];
        if(!in_array(['X' => $x, 'Y' => $y], $this -> moved)){
            $this -> moved[] = ['X' => $x, 'Y' => $y];
        }
        if($direction === '0'){
            $this -> setNow(['X' => $x, 'Y' => $y-1]);
        }
        if($direction === '1'){
            $this -> setNow(['X' => $x+1, 'Y' => $y]);
        }
        if($direction === '2'){
            $this -> setNow(['X' => $x, 'Y' => $y+1]);
        }
        if($direction === '3'){
            $this -> setNow(['X' => $x-1, 'Y' => $y]);
        }
        $this -> setFace($direction);
    }

    public function setFace($num){
        $this -> face = $num;
    }

    public function PrintMaze(){
        for($y = 0; $y < $this -> height; $y++){
            for($x = 0; $x < $this -> width; $x++){
                if($x === $this -> now['X'] && $y === $this -> now['Y']){
                    switch($this -> face){
                        case Zone::NORTH :
                            echo '<span class="'.$x.'-'.$y.' mapcell now"><i class="fas fa-arrow-circle-up"></i></span>';
                            break;
                        case Zone::SOUTH :
                            echo '<span class="'.$x.'-'.$y.' mapcell now"><i class="fas fa-arrow-circle-down"></i></span>';
                            break;
                        case Zone::WEST :
                            echo '<span class="'.$x.'-'.$y.' mapcell now"><i class="fas fa-arrow-circle-left"></i></span>';
                            break;
                        case Zone::EAST :
                            echo '<span class="'.$x.'-'.$y.' mapcell now"><i class="fas fa-arrow-circle-right"></i></span>';
                            break;
                    }
                }else if($x === $this -> nextFloor['X'] && $y === $this -> nextFloor['Y']){
                    if(!in_array(['X' => $x, 'Y' => $y], $this -> moved)){
                        echo '<span class="'.$x.'-'.$y.' mapcell masked">▲</span>';
                    }else{
                    echo '<span class="'.$x.'-'.$y.' mapcell moved">▲</span>';
                    }
                }else if($this -> maze[$x][$y] === 'Wall'){
                    echo '<span class="'.$x.'-'.$y.' mapcell masked">■</span>';
                }else{
                    if(!in_array(['X' => $x, 'Y' => $y], $this -> moved)){
                        echo '<span class="'.$x.'-'.$y.' mapcell">　</span>';
                    }else{
                        echo '<span class="'.$x.'-'.$y.' mapcell moved">　</span>';
                    }
                } 
            }
            echo '<br>';
        }
    }
}


abstract class Creature{
    protected $name;
    protected $hp;
    protected $attack;
    protected $exp;

    const coefficient = 1.3;

    public function __construct($name, $hp, $attack, $exp){
        $this -> name = $name;
        $this -> hp = $hp;
        $this -> attack = $attack;
        $this -> exp = $exp;
    }
    public function getName(){
        return $this -> name;
    }
    public function setHp($num){
        $this -> hp = $num;
    }
    public function getHp(){
        return $this -> hp;
    }
    public function getExp(){
        return $this -> exp;
    }
    public function attack($target){
        $damage = (int)mt_rand($this -> attack, $this -> attack * self::coefficient);

        if(!mt_rand(0,19)){
            $damage = $damage * 1.5;
            $damage = (int)$damage;
            History::set($this -> name.'のクリティカルヒット！');
        }else{
            History::set($this -> name.'の攻撃！');
        }
        $target -> setHp($target -> getHp() - $damage);
        History::set($target -> getName().'に'.$damage.'のダメージ！');
    }

}

class Human extends Creature{
    protected $cure;
    protected $maxHp;
    protected $lv;
    protected $requiredExp;

    const lvCoefficient = 1.2;

    public function __construct($name, $hp, $attack, $exp, $cure, $maxHp, $lv, $requiredExp){
        parent::__construct($name, $hp, $attack, $exp);
        $this -> cure = $cure;
        $this -> maxHp = $maxHp;
        $this -> lv = $lv;
        $this -> requiredExp = $requiredExp;
    }
    public function getMaxHp(){
        return $this -> maxHp;
    }
    public function getLv(){
        return $this -> lv;
    }
    public function getRequiredExp(){
        return $this -> requiredExp;
    }

    public function cure(){
        $heal = (int)mt_rand($this -> cure, $this -> cure * self::coefficient);
        $this -> hp = $this -> hp + $heal;
        History::set($this -> name.'のHPが'.$heal.'回復した');
        if($this -> hp >= $this -> maxHp){
            $this -> hp = $this -> maxHp;
        }
    }
    public function addExp($target){
        $this -> exp = $this -> exp + $target -> getExp();
        if($this -> exp >= $this -> requiredExp){
            $this -> lvUp();
        }
    }

    public function lvUp(){
        $this -> exp = $this -> exp - $this -> requiredExp;
        $this -> lv = $this -> lv +1;
        History::set($this -> name.'のレベルが'.$this -> lv.'に上がった！');
        $this -> maxHp = (int)($this -> maxHp * self::lvCoefficient);
        $this -> attack = (int)($this -> attack * self::lvCoefficient);
        $this -> cure = (int)($this -> cure * self::lvCoefficient);
        $this -> hp = $this -> maxHp;
    }
}

class Monster extends Creature{
    protected $img;

    public function __construct($name, $hp, $attack, $exp, $img){
        parent::__construct($name, $hp, $attack, $exp);
        $this -> img = $img;
    }
    public function getImg(){
        return $this -> img;
    }
}

class History{
    public static function set($str){
        if(empty($_SESSION['history'])){
            $_SESSION['history'] = '';
        }
        $_SESSION['history'] .= $str.'<br>';
    }
    public static function clear(){
        $_SESSION['history'] = array();
    }
}

$human = new Human('勇者', 500, 30, 0, 100, 500, 1, 100);

$monsters = array();
$monsters[] = new Monster( 'フランケン', 100, 20, 20, 'img/monster01.png');
$monsters[] = new Monster( 'フランケンNEO', 300, 40, 40, 'img/monster02.png');
$monsters[] = new Monster( 'ドラキュリー', 200, 20, 30, 'img/monster03.png');
$monsters[] = new Monster( 'ドラキュラ男爵', 400, 40, 40, 'img/monster04.png');
$monsters[] = new Monster( 'スカルフェイス', 150, 40, 30, 'img/monster05.png');
$monsters[] = new Monster( '毒ハンド', 100, 50, 30, 'img/monster06.png');
$monsters[] = new Monster( '泥ハンド', 120, 20, 20, 'img/monster07.png');
$monsters[] = new Monster( '血のハンド', 180, 30, 30, 'img/monster08.png');

$boss = new Monster('ゴブリン', 500, 60, 100, 'img/boss.png');

function createMonster(){
    global $monsters;
    $monster = $monsters[mt_rand(0, 7)];
    $_SESSION['monster'] = $monster;
    History::set($_SESSION['monster'] -> getName().'が現れた！');
}

function createBoss(){
    global $boss;
    error_log('boss');
    $_SESSION['monster'] = $boss;
    History::set($_SESSION['monster'] -> getName().'が現れた！');
}

function createHuman(){
    global $human;
    $_SESSION['human'] = $human;
}

function encount(){
    if(!mt_rand(0, 4)){
        createMonster();
        History::set($_SESSION['monster'] -> getName().'が現れた！');
    }
}

function init(){
    History::clear();
    $_SESSION = array();
    createHuman();
    $map = new Map(9,9);
    $_SESSION['map'] = $map;
    $_SESSION['map'] -> createMaze();
    $_SESSION['map'] -> setStartAndNextFloor();
    $_SESSION['floor'] = 1;
}

function goNext(){
    $_SESSION['map'] -> createMaze();
    $_SESSION['map'] -> setStartAndNextFloor();
    $_SESSION['floor'] = $_SESSION['floor'] + 1;
    $_SESSION['map'] -> clearMoved();
}

function gameOver(){
    $_SESSION['map'] = '';
}


if(!empty($_POST)){
    if(!empty($_POST['start'])){
        init();
        History::set('ゲームスタート！');
    }
    if(!empty($_SESSION['map'])){
        $direction = $_SESSION['map'] -> MovableDirection();
        if(isset($_POST['direction'])){
            $_SESSION['map'] -> MovedPosition($_POST['direction']);
            $direction = $_SESSION['map'] -> MovableDirection();
            encount();
        }
        if(!empty($_POST['goNext'])){
            goNext();
            $direction = $_SESSION['map'] -> MovableDirection();
            if($_SESSION['floor'] === 5){
                createBoss();
            } 
        }
        if(!empty($_POST['attack'])){
            History::set($_SESSION['human'] -> getName().'の攻撃');
            $_SESSION['human'] -> attack($_SESSION['monster']);

            if($_SESSION['monster'] -> getHp() <= 0){
                History::set($_SESSION['monster'] -> getName().'を倒した！');
                History::set($_SESSION['monster'] -> getExp().'の経験値を取得');
                $_SESSION['human'] -> addExp($_SESSION['monster']);
                $_SESSION['monster'] = '';
            }
            if(!empty($_SESSION['monster'])){
                History::set($_SESSION['monster'] -> getName().'の攻撃');
                $_SESSION['monster'] -> attack($_SESSION['human']);

                if($_SESSION['human'] -> getHp() <= 0){
                    History::set($_SESSION['human'] -> getName().'は'.$_SESSION['monster'] -> getName().'に倒された');
                    gameOver();
                }
            }
        }
        if(!empty($_POST['cure'])){
            History::set($_SESSION['human'] -> getName().'は回復薬を使った');
            $_SESSION['human'] -> cure();

            History::set($_SESSION['monster'] -> getName().'の攻撃');
            $_SESSION['monster'] -> attack($_SESSION['human']);

            if($_SESSION['human'] -> getHp() <= 0){
                History::set($_SESSION['human'] -> getName().'は'.$_SESSION['monster'] -> getName().'に倒された');
                gameOver();
            }
        }
        if(!empty($_POST['escape'])){
            History::set($_SESSION['human'] -> getName().'は逃げ出した');
            $_SESSION['monster'] = '';
        }
        if(!empty($_POST['restart'])){
            gameOver();
        }
    }
}

?>


<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <style>
        .body {
            overflow: hidden;
        }

        section {
            margin-right: 20px;
        }

        #map {
            min-width: 100px;
            min-height: 200px;
            float: left;
        }

        .map {
            line-height: 0.9;
            letter-spacing: -0.2em;
        }

        .mapcell {
            opacity: 0;
        }

        .now {
            opacity: 1;
        }

        #main {
            width: 300px;
            height: 500px;
            float: left;
        }

        #history {
            width: 300px;
            height: 300px;
            float: left;
        }

        .history {
            height: 300px;
            overflow: scroll;
            border: 1px solid black;
        }

        .direction {
            position: relative;
            height: 300px;
            width: 200px;
            margin: auto;
            top: 20px;
        }

        .direction0 {
            height: 30px;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .direction1 {
            height: 30px;
            position: absolute;
            top: 40px;
            right: 0;
        }

        .direction2 {
            height: 30px;
            position: absolute;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
        }

        .direction3 {
            height: 30px;
            position: absolute;
            top: 40px;
            left: 0;
        }

        .directionNext {
            height: 30px;
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            color: red;
        }

        #start {
            margin: 100px auto;
            width: 100px;
        }

        input[type="submit"] {
            font-size: 16px;
            background: none;
            border: 2px solid black;
        }

        #img {
            background-image: url(img/back.jpg);
            background-size: cover;
            position: relative;
            width: 300px;
            height: 300px;
        }

        #img2 {
            background-image: url(img/step.jpg);
            background-size: cover;
            position: relative;
            width: 300px;
            height: 300px;
        }

        .monster {
            width: 150px;
            height: 150px;
            margin: auto;
            padding-top: 20px;
            padding-bottom: 20px;
            display: block;
        }

        .battle {
            background-color: azure;
            font-size: 18px;
            padding: 10px;
            position: absolute;
            bottom: 0;
            margin: auto;
            width: 300px;
            box-sizing: border-box;
        }

    </style>
</head>

<body>
    <div class="body">
        <?php if(empty($_SESSION['map'])){ ?>
        <section id="map">
        </section>
        <section id="main">
            <form method='post' id="start">
                <input type="submit" name="start" value="ゲームスタート">
            </form>
        </section>
        <?php }else{ ?>

        <section id="map">
            マップ<br>
            現在のフロア：
            <?php echo $_SESSION['floor']; ?><br>
            <div class="map">
                <?php $_SESSION['map'] -> PrintMaze(); ?>
            </div><br>
            <form method='post' id="restart">
                <input type="submit" name="restart" value="リスタート">
            </form>
        </section>

        <section id="main">
            メイン<br>
            <?php if($_SESSION['map'] -> getNow() === $_SESSION['map'] -> getNextFloor()){ ?>
            <div id="img2">
                <?php }else{ ?>
                <div id="img">
                    <?php } ?>
                    <?php if(empty($_SESSION['monster'])){ ?>
                    <form method="post">
                        <div class="direction">
                            <?php for($i = 0; $i < 4; $i++){ ?>
                            <?php if(in_array($i, $direction)){ ?>
                            <button type="submit" name="direction" class="direction<?php echo (($i - $_SESSION['map'] -> getFace() +4) %4 ); ?>" value="<?php echo $i; ?>">
                                <?php echo Zone::getZone(($i - $_SESSION['map'] -> getFace() +4) %4 ); ?>に進む" </button>
                            <?php } ?>
                            <?php } ?>
                            <?php if($_SESSION['map'] -> getNow() === $_SESSION['map'] -> getNextFloor()){ ?>
                            <button type="submit" name="goNext" class="directionNext" value="next">次の階へ！</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
                <?php }else{ ?>
                <img src="<?php echo $_SESSION['monster'] -> getImg(); ?>" class="monster">
                <form method="post" class="battle">
                    <div>
                        <?php echo $_SESSION['monster'] -> getName(); ?><br>
                        モンスターのHP：
                        <?php echo $_SESSION['monster'] -> getHp(); ?>
                    </div>
                    <input type="submit" name="attack" value="攻撃する">
                    <input type="submit" name="cure" value="回復する">
                    <input type="submit" name="escape" value="逃げる">
                </form>
            </div>
            <?php } ?>
            <div class="prayer">
                HP：
                <?php echo $_SESSION['human'] -> getHp(); ?> /
                <?php echo $_SESSION['human'] -> getMaxHp(); ?><br>
                Lv:
                <?php echo $_SESSION['human'] -> getLv(); ?><br>
                EXP:
                <?php echo $_SESSION['human'] -> getExp(); ?> /
                <?php echo $_SESSION['human'] -> getRequiredExp(); ?><br>
            </div>
        </section>
        <?php } ?>

        <section id="history" data-name="11">
            履歴
            <div class="history">
                <?php echo $_SESSION['history']; ?>
            </div>
        </section>
    </div>
</body>
<footer>
    <script src="jquery01/jquery-3.3.1.min.js"></script>
    <script>
        var obj = document.querySelector('.history');
        obj.scrollTop = obj.scrollHeight;
        $(function() {
            <?php if (!empty($_SESSION['map'])) { ?>
            var $now = $('.now');
            var $cell = $('.mapcell');
            var nowx = <?php echo $_SESSION['map'] -> getNow()['X']; ?>;
            var nowy = <?php echo $_SESSION['map'] -> getNow()['Y']; ?>;
            var face = <?php echo $_SESSION['map'] -> getFace(); ?>;

            $now.removeClass('now').text('　');
            $('.' + (nowx + 1) + '-' + nowy).css({
                opacity: 1
            });
            $('.' + (nowx - 1) + '-' + nowy).css({
                opacity: 1
            });
            $('.' + nowx + '-' + (nowy + 1)).css({
                opacity: 1
            });
            $('.' + nowx + '-' + (nowy - 1)).css({
                opacity: 1
            });

            var mark = '';
            switch (face) {
                case <?php echo Zone::NORTH; ?>:
                    mark = '<i class="fas fa-arrow-circle-up"></i>';
                    break;
                case <?php echo Zone::SOUTH; ?>:
                    mark = '<i class="fas fa-arrow-circle-down"></i>';
                    break;
                case <?php echo Zone::WEST; ?>:
                    mark = '<i class="fas fa-arrow-circle-left"></i>';
                    break;
                case <?php echo Zone::EAST; ?>:
                    mark = '<i class="fas fa-arrow-circle-right"></i>';
                    break;
            }

            $('.' + nowx + '-' + nowy).addClass('now').html(mark);

            <?php foreach($_SESSION['map'] -> getMoved() as $key => $val){ ?>
            var movedx = <?php echo $val['X']; ?>;
            var movedy = <?php echo $val['Y']; ?>;
            $('.' + (movedx + 1) + '-' + movedy).css({
                opacity: 1
            });
            $('.' + (movedx - 1) + '-' + movedy).css({
                opacity: 1
            });
            $('.' + movedx + '-' + (movedy + 1)).css({
                opacity: 1
            });
            $('.' + movedx + '-' + (movedy - 1)).css({
                opacity: 1
            });

            <?php } ?>

            console.log(nowx);
            console.log(nowy);
            console.log(nowx + '-' + nowy);
            <?php } ?>
        });

    </script>
</footer>

</html>
