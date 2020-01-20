{{-- header_footer.blade.phpの内容を読み込む --}}
@extends('layouts.header_footer')

{{--ページコンテンツ--}}
@section('top-research')

<!-- 楽天のAPI表示 -->
<?php
$rakuten_relust = getRakutenResult('進撃の巨人', 1000); // キーワードと最低価格を指定
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
<!-- Yahoo!のAPI表示 -->
<?php

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
</html>
<!-- 楽天のAPI処理 -->
<?php 

function getRakutenResult($keyword, $min_price) {

// ベースとなるリクエストURL
$baseurl = 'https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706';
// リクエストのパラメータ作成
$params = array();
$params['applicationId'] = '1028872143579553215'; // アプリID
$params['keyword'] = urlencode_rfc3986($keyword); // 任意のキーワード。※文字コードは UTF-8
$params['sort'] = urlencode_rfc3986('+itemPrice'); // ソートの方法。※文字コードは UTF-8
$params['minPrice'] = $min_price; // 最低価格

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

return $items;
}
?>
<!-- YahooのAPI処理 -->
<?php

function getYahooResult($keyword) {

// リクエストURL
$baseurl = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch"; //XML
// リクエストのパラメータ作成
$params = array();
$params["appid"] = "dj00aiZpPU1HSTV5aTRLcVkwMCZzPWNvbnN1bWVyc2VjcmV0Jng9MGQ-"; // アプリケーションID
$params["query"] = urlencode_rfc3986($keyword);
$params["sort"] = urlencode_rfc3986("+price");

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

$items = array();
foreach($yahoo_xml->Result->Hit as $item){

$items[] = array(
'name' => (string)$item->Name,
'url' => (string)$item->Url,
'img' => (string)$item->Image->Medium,
'price' => (string)$item->Price,
);

}
return $items;
}
// RFC3986 形式で URL エンコードする関数
function urlencode_rfc3986($str) {
    return str_replace('%7E', '~', rawurlencode($str));
}
?>
@endsection