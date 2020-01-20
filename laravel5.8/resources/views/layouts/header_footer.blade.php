<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- 各ページごとにtitleタグを入れるために@yieldで空けておきます。 --}}
        <title>@yield('title')</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        {{-- CSSを読み込みます --}}
        <link href="{{ secure_asset('css/header.css') }}" rel="stylesheet">
    </head>
    <body>
      <header>
         <div class="site-title">訳アリ商品リサーチ！</div>
         <div class="site-sub-title">在庫性分などお得商品も<div>
         <form method="get" action="http://www.google.co.jp/search" target="_blank">
            <input type="text" search ="商品を探す" name="q" size="31" maxlength="255" value="">
            <input type="submit" name="btng" value="検索">
            <input type="hidden" name="hl" value="ja">
         </form>
         <div class="header-menu"><img src="" width="130px" height="160px" boder="2px" alt="フルーツ"><div>
         <div class="header-menu"><img src="" width="130px" height="160px" boder="2px" alt="海鮮"><div>
         <div class="header-menu"><img src="" width="130px" height="160px" boder="2px" alt="お肉"><div> 
      </header>
      <main class="main">
      {{-- ページコンテンツの内容がここに入る --}}
         @yield('content')
         @yield('top-research')
      </main>
      <footer>
         <p class="copyright">(c) 2020 訳アリ商品リサーチ！</P>
      </footer>   
    </body>
<html>            