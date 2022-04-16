<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/fav.png" type="png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">

    <title>Gratus</title>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .logo {
            text-align: center;
            margin-top: 26px;
        }
        .header-name{
            text-align: center;
            margin-top: 46px;
        }
        h1 {
            font-family: 'Nunito', sans-serif;
            font-size: 36px;
            font-weight: 700;
        }
        .main-b {
            max-width: 504px;
            height: 283px;
            background-color: #FFE3E3;
            margin: 0 auto;
            margin-top: 17px;
        }
        .main-b-w {
            max-width: 504px;
            height: 283px;
            background-color: #D3FAFF;
            margin: 0 auto;
            margin-top: 17px;
            position: relative;
            text-align: center;
            margin-bottom: 42px;
        }
        .name-e{
            font-family: 'Nunito', sans-serif;
            font-size: 19px;
            font-weight: 400;
            text-align: center;
            padding-top: 10px;
        }
        .name-e-s {
            font-family: 'Nunito', sans-serif;
            font-size: 19px;
            font-weight: 400;
            text-align: center;
        }
        .name-e-s p {
            padding-top: 25px;
            margin: 0;
        }
        .name-w{
            margin: -10px 0 -10px 0;
        }
        .name-e p{
            padding-top: 25px;
            margin: 0;
        }
        .main-b {
            position: relative;
            text-align: center;
            margin-bottom: 150px;
        }
        .main-b-r {
            position: relative;
            text-align: center;
            margin: 0 auto;
            margin-bottom: 225px;
            max-width: 504px;
            height: 283px;
            background-color: #FFE3E3;

            margin-top: 17px;
        }
        .main-i{
            padding-top: 20px;
            text-align: center;
        }
        .main-p p {
            font-size: 19px;
            font-weight: 400;
            text-align: center;
            margin-top: 37px;
        }
        .main-pp p {
            font-size: 19px;
            font-weight: 400;
            text-align: center;
            margin: 0;
        }
        .main-pp {
            margin-top: 37px;
        }
        .button-m {
            width: 259px;
            height: 48px;
            background-color: #FFB800;
            color: #fff;
            border-radius: 4px;
            border: none;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
        }
        .button-m-a {
            width: 259px;
            height: 48px;
            background-color: #FFB800;
            color: #fff;
            border-radius: 4px;
            border: none;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 10px;
        }
        .foot-d{
            height: 2px;
            width: 80%;
            background-color: #000;
            margin: 0 auto;
        }
        .foot-t {
            text-align: center;
            padding-top: 51px;
        }
        a {
            color: #0084ff;
        }
        .imgs img {
            margin-right: 35px;
        }
        .imgs {
            padding-left: 35px;
        }
        .ios{
            margin-top: -10px;
        }
        .city-s{
            margin-top: -10px;
        }
        .unsub-s {
            text-decoration: underline;
            cursor: pointer;
        }
        .chng-p {
            width: 504px;
        }
        .main-b-n{
            margin-bottom: 90px;
            background-color:  #FFC28A;
        }
        .main-b-ng {
            margin-bottom: 24px;
            background-color: #A6EDC2;;
        }
        .main-b-s{

            background-color: #DEFFFD;
        }
        .s-two{
            transform: rotate(180deg);
        }
    </style>
  </head>
  <body>
    <header>
        <div class="logo">
            <a href="/"><img src="{{ asset('storage/emails/img/Logo_gratus.svg') }}" alt="Gratus"></a>
        </div>
    </header>

    <section>
        <div class="header-name">
            <h1>Successfully accepted</h1>
        </div>
        <div class="main-b-w">
            <div>
                <h2 class="name-w">You have successfully accepted invite from an organization</h2>
            </div>
            <div class="main-i">
                <img src="{{ asset('storage/emails/img/handsphone.png') }}" alt="">
            </div>
        </div>
    </section>
  </body>
