<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
<h1>Hello, world!</h1>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<div class="container">
    İlk Bootstarap kodum !
</div>

</body>
</html>
<?php
class arac{
    public $araclar = "";
    public $markalar = "";
    public $modeller = "";
    public function marka(){
        $this->araclar->markalar['arac']['marka'][] = "ford";
        $this->araclar->markalar['arac']['marka'][] = "reno";
        $this->araclar->markalar['arac']['marka'][] = "citroen";
        return $this;
    }
    public function model(){
        //$this->markalar['modeller'];
    }
}

$marka = new arac();
$sonuc = $marka->marka();
print_r($sonuc);
?>