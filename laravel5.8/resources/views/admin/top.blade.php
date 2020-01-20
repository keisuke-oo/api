<!--　
    01/16：foreachで楽天とYahooをソートする
　　2つを一緒にやる
　-->

{{-- header_footer.blade.phpの内容を読み込む --}}
@extends('layouts.header_footer')

{{-- ページタイトル --}}
@section('title','訳アリ商品リサーチ！')

{{--ページコンテンツ--}}
@section('content')
<!-----------------サイトメニュー一覧----------------------->
<div class="sm-menu"><img src="" width="130px" height="160px" boder="2px" alt="フルーツ"><div>
<div class="sm-menu"><img src="" width="130px" height="160px" boder="2px" alt="海鮮"><div>
<div class="sm-menu"><img src="" width="130px" height="160px" boder="2px" alt="お肉"><div> 
<div class="sm-menu"><img src="" width="130px" height="160px" boder="2px" alt="飲み物"><div> 
<div class="sm-menu"><img src="" width="130px" height="160px" boder="2px" alt="生活用品"><div> 
<div class="sm-menu"><img src="" width="130px" height="160px" boder="2px" alt="アウトレット"><div> 
<br>
</html>
<?php
// -----------------楽天のAPI検索結果をスタイルに当てはめる-----------------------
$rakuten_relust = getRakutenResult('進撃の巨人'); // キーワードと最低価格を指定
foreach ($rakuten_relust as $item) :
?>
<div style='margin-bottom: 20px; padding: 30px; border: 1px solid #000; overflow:hidden;'>
 <div style='float: left;'><img src='<?php echo $item['img']; ?>'></div>
 <div style='float: left; padding: 20px;'>
 <div><?php echo $item['name']; ?></div>
 <div><a href='<?php echo $item['url']; ?>' target="_blank"><?php echo $item['url']; ?></a></div>
 <div><?php echo $item['price']; ?>円</div>
 <div><?php echo $item['shop']; ?></div>
 </div>
</div>
<?php
endforeach;
?>
<?php
// -----------------YahooのAPI検索結果をスタイルに当てはめる-----------------------
$yahoo_relust = getYahooResult('進撃の巨人'); // キーワードと最低価格を指定
foreach ($yahoo_relust as $item) :
?>
<div style='margin-bottom: 20px; padding: 30px; border: 1px solid #000; overflow:hidden;'>
<div style='float: left;'><img src='<?php echo $item['img']; ?>'></div>
<div style='float: left; padding: 20px;'>
<div><?php echo $item['name']; ?></div>
<div><a href='<?php echo $item['url']; ?>' target="_blank"><?php echo $item['url']; ?></a></div>
<div><?php echo $item['price']; ?>円</div>
</div>
</div>
<?php
endforeach;
?>

<!-----------------リクエストURLの作成----------------------->
<?php 
//------------------楽天のAPIのリクエストURL作成処理--------------------

function getRakutenResult($keyword) {

// ベースとなるリクエストURL
$baseurl = 'https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706';
// リクエストのパラメータ作成
$params = array();
$params['applicationId'] = '1028872143579553215'; // アプリID
$params['keyword'] = urlencode_rfc3986($keyword); // 任意のキーワード。※文字コードは UTF-8
$params['sort'] = urlencode_rfc3986('+itemPrice'); // ソートの方法。※文字コードは UTF-8

$canonical_string='';

foreach($params as $k => $v) {
    $canonical_string .= '&' . $k . '=' . $v;
}
// 先頭の'&'を除去
$canonical_string = substr($canonical_string, 1);

// リクエストURL を作成
$url = $baseurl . '?' . $canonical_string;

// XMLをオブジェクトに代入
$rakuten_json=json_decode(@file_get_contents($url, true));
}
//------------------YahooのAPIのリクエストURL作成処理--------------------

function getYahooResult($keyword) {

    // リクエストURL
    $baseurl = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch"; //XML
    // リクエストのパラメータ作成
    $params = array();
    $params["appid"] = "dj00aiZpPU1HSTV5aTRLcVkwMCZzPWNvbnN1bWVyc2VjcmV0Jng9MGQ-"; // アプリケーションID
    $params["query"] = urlencode_rfc3986($keyword);
    
    // canonical string を作成
    $canonical_string = "";
    
    foreach ($params as $k => $v) {
    $canonical_string .= "&" . $k . "=" . $v;
    }
    // 先頭の'&'を除去
    $canonical_string = substr($canonical_string, 1);
    
    // URL を作成
    $url = $baseurl . "?" . $canonical_string;
    
    echo $url . "<br>";
    
    // XMLをオブジェクトに代入
    $yahoo_xml = simplexml_load_string(@file_get_contents($url));
}
//------------------各リクエストURLを出力処理--------------------    


//----------楽天＿リクエストURLを出力処理----------------  
$items = array();
foreach($rakuten_json->Items as $item) {
    $items[] = array(
                    'name' => (string)$item->Item->itemName,
                    'url' => (string)$item->Item->itemUrl,
                    'img' => isset($item->Item->mediumImageUrls[0]->imageUrl) ? (string)$item->Item->mediumImageUrls[0]->imageUrl : '',
                    'price' => (string)$item->Item->itemPrice,
                    'shop' => (string)$item->Item->shopName,
                    );
}


//----------Yahoo＿リクエストURLを出力処理----------------  
$items = array();
foreach($yahoo_xml->Result->Hit as $item){

$items[] = array(
'name' => (string)$item->Name,
'url' => (string)$item->Url,
'img' => (string)$item->Image->Medium,
'price' => (string)$item->Price,
);

}

array_merge( $rakuten_relust,$yahoo_relust);
print_r($result);

// RFC3986 形式で URL エンコードする関数
function urlencode_rfc3986($str) {
    return str_replace('%7E', '~', rawurlencode($str));
}
?>
@endsection