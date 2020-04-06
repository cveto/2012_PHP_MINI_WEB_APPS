

<html>
<head>
<meta charset="utf-8"> 
<script charset="UTF-8"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

//////This just gets the name of the current webpage
var path = window.location.pathname;
var thiswebpage = path.substr( path.lastIndexOf("/") + 1 );


/////This sends and reseives data from the PHP, though its kinda stupid on the receiving part since it just receives an echo and thats it.
/////Apparnetly Johnsons or something like that is neede
function romanjitohiragana(){
 var JQUERYvar = $('#FORMvar').val();
 
 $.ajax
 ({
 type: "GET",
 url: thiswebpage,
 data: {PHPvar:JQUERYvar}
 })
 
 .done(function( dobio ) 
 {
 $("#msg").html(dobio);
 }
 );
}



</script>
</head>
<body>

<input type=text style="overflow:auto;resize:none" size="100" type="textbox" name="FORMvar" id="FORMvar" value="こんいちは！" />
<input type="button" onClick="romanjitohiragana()" value="Click the TOP button"/>

<div id="msg"></div>
</body>
</html>


<?php
error_reporting(E_ALL ^ E_NOTICE);
////Its on this locations (stupidly, I know) just so it makes the webpae less ugly.

///This was needed for mb functions. Debugging purposes. Probably can be deletted now.
mb_internal_encoding("UTF-8");


///The brain of the programm.

if (isset($_GET[PHPvar])) {

$receivedText = $_GET["PHPvar"];
$arrayOfReceivedText = str_split($receivedText,3);
$romanji = array();
$romanji_string;

//presledek v hiragani ni isti kot presledek v latinici!!
$HiraganaArray = array('　',
					'あ','い','う','え','お',
					'か','き','く','け','こ',
					'さ','し','す','せ','そ',
					'た','ち','つ','て','と',
					'な','に','ぬ','ね','の',
					'は','ひ','ふ','へ','ほ',
					'ま','み','む','め','も',
					'や','ゆ','よ',
					'ら','り','る','れ','ろ',
					'わ','を',
					'ん',
					'が','ぎ','ぐ','げ','ご',
					'ざ','じ','ず','ぜ','ぞ',
					'だ','ぢ','づ','で','ど',
					'ば','び','ぶ','べ','ぼ',
					'ぱ','ぴ','ぷ','ぺ','ぽ',
					'ゃ','ゅ','ょ',
					'。','、'
					);
					
$KatakanaArray = array('　',
					'ア','イ','ウ','エ','オ',
					'カ','キ','ク','ケ','コ',
					'サ','シ','ス','セ','ソ',
					'タ','チ','ツ','テ','ト',
					'ナ','ニ','ヌ','ネ','ノ',
					'ハ','ヒ','フ','ヘ','ホ',
					'マ','ミ','ム','メ','モ',
					'ヤ','ユ','ヨ',
					'ラ','リ','ル','レ','ロ',
					'ワ','ヲ',
					'ン',
					'ガ','ギ','グ','ゲ','ゴ',
					'ザ','ジ','ズ','ゼ','ゾ',
					'ダ','ヂ','ヅ','デ','ド',
					'バ','ビ','ブ','ベ','ボ',
					'パ','ピ','プ','ペ','ポ',
					'ヤ','ユ','ヨ',
					'。','、'
					);




$RomanjiArray = array(' ',
					'a','i','u','e','o',
					'ka','ki','ku','ke','ko',
					'sa','shi','su','se','so',
					'ta','chi','tu','te','to',
					'na','ni','nu','ne','no',
					'ha','hi','hu','he','ho',
					'ma','mi','mu','me','mo',
					'ya','yu','yo',
					'ra','ri','ru','re','ro',
					'wa','wo',
					'n',
					'ga','gi','gu','ge','go',
					'za','zi','zu','ze','zo',
					'da','di','du','de','do',
					'ba','bi','bu','be','bo',
					'pa','pi','pu','pe','po',
					'ya','yu','yo',
					'. ',',　'
					);

//Check for equivalent Romanji in the Arrays, and if finds it, places it in romanji folder.
//For all the katakana or hiragana characters, check in Katakana and hiragana arrays for equivalent
// and if you find it, add it to the romanji array.

for ($i=0; $i<count($arrayOfReceivedText); $i++)	{
	for ($j=0; $j<=count($HiraganaArray); $j++)	{
		if (($arrayOfReceivedText[$i] == $KatakanaArray[$j]) ||
			($arrayOfReceivedText[$i] == $HiraganaArray[$j]))
				{
			$romanji[$i] = $RomanjiArray[$j];
		}
	}
}
$romanji_string = implode($romanji);
echo $romanji_string;
/*
for ($k=0; $k<count($HiraganaArray); $k++)	{
	echo $HiraganaArray[$k];
	echo ' JE ';
	echo $RomanjiArray[$k];
	echo '<br />';
}
*/

//print_r($arrayOfReceivedText);
//echo '<br /> romanji';
//print_r($romanji);



//echo 'bla lba ',$romanji[0],$romanji[0],$romanji[0];

/*
echo '<br /> strlen: ', strlen($receivedText[0]);
echo '<br /> strlensub: ', strlen(mb_substr($receivedText,0,1,'utf-8'));
echo '<br /> mbstrlensub: ', mb_strlen(mb_substr($receivedText,0,1,'utf-8'),'utf-8');
echo '<br /> mbstrlensub simplifed: ', mb_strlen(mb_substr($receivedText,0,1));
echo '<br /> Prejeti streing: ', $receivedText[0];
echo '<br /> PRejeti prvi character po encodingu: ',$arrayOfReceivedText[0];
echo '<br /> upajmo na najbolje',$receivedText[0],$receivedText[1],$receivedText[2];
*/

//echo $_GET[PHPvar];
}
?> 