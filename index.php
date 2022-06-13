<?php

require __DIR__ . '/vendor/autoload.php';


class index
{
    private $height;
    private string $nonce;
    private string $difficulty;
    private string $date;
    private string $merkel;
    private string $previousBlockHash;
    private string $version;

    //mining bitcoins
    public function index()
    {

        //get last mined bitcoin from api
//        $lastBitcoin = $this->getLastBlockMined();

        //get last block header
//        $lastBlockHeader = $this->getLastBlockHeader($lastBitcoin->previousBlockHash);
        $lastBlockHeader = $this->getLastBlockHeader("000000000000000000068643fa09c1fc5842db0a99cf337e72424d17bb0c9678");
        // Nonce
//        $this->nonce = $this->generateRandomBlockchainNonceWithALengthOf8();
        $this->nonce = dechex(2340225404);

        //Height
        $this->height = $lastBlockHeader->height;

        // Merkel
        $this->merkel = $lastBlockHeader->merkle_root;

        // Date
        $this->date = $lastBlockHeader->timestamp;

        // Previous Hash
        $this->previousBlockHash = $lastBlockHeader->previousblockhash;

        // Version
        $this->version = dechex($lastBlockHeader->version);

        // Difficulty
        $this->difficulty = dechex($lastBlockHeader->bits);

        return [
            "height" => $this->height,
            "nonce" => $this->nonce,
            "difficulty" => $this->difficulty,
            "date" => $this->date,
            "merkel" => $this->merkel,
            "previousBlockHash" => $this->previousBlockHash,
            "version" => $this->version,
        ];
    }



    private function getLastBlockMined()
    {
        //Request curl to api to get last mined bitcoin block
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://blockchain.info/latestblock',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        return  json_decode($resp);
    }

    private function getLastBlockHeader($previousBlockHash)
    {
        //Request curl to api to get last block header
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://blockstream.info/api/block/' . $previousBlockHash,
//            CURLOPT_URL => 'https://blockchain.info/rawblock/' . $previousBlockHash,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return  json_decode($resp);
    }

    private function generateRandomBlockchainNonceHexa(): string
    {
        $nonce = "";
        for ($i = 0; $i < 8; $i++) {
            $nonce .= dechex(rand(0, 15));
        }
        return $nonce;
    }

    private function generateRandomBlockchainNonceWithALengthOf8(): string
    {
        $nonce = "";
        for ($i = 0; $i < 8; $i++) {
            $nonce .= dechex(rand(0, 15));
        }
        return $nonce;
    }

    private function convertDecimalToHexa($decimal)
    {
        $decimal = 388618029;
        $hexadecimal = "";
        while ($decimal > 0) {
            $hexadecimal = dechex($decimal % 16) . $hexadecimal;
            $decimal = intval($decimal / 16);
        }
        return $hexadecimal;
    }

    private function getFirstByte($hexadecimal)
    {
        return substr($hexadecimal, 0, 2);
    }

    private function getLastThreeBytes($hexadecimal)
    {
        return substr($hexadecimal, 2, 6);
    }

    public function  convertTimestampToHexadecimal($date)
    {
        return dechex($date);
    }

    private function calculTarget($index, $coefficient)
    {
        return hexdec($coefficient) * bcpow(
            2,
            8 * (hexdec($index) - 3),
            2
        );
    }

    public function addParam($chain, $newParam )
    {
        return $chain . $newParam;
    }

    public function splitChainWithLengthOf2($chain)
    {
        return str_split($chain, 2);
    }

    public function reverseArray($array){
        return array_reverse($array);
    }

    public function implodeArray($array){
        return implode($array);
    }

    public function convertHexadecimalToBinary($hexadecimal)
    {
        return hex2bin($hexadecimal);
    }

    public function hashWithSha256($chain)
    {
        return hash('sha256', $chain);
    }
}

$blockMiner = new index();
$lastBlockHeader = $blockMiner->index();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Last Mined Block Header</title>
        <!-- bootstrap 5 cdn       -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
        <style>
            p   {
                word-break: break-all;
            }
        </style>
    </head>
    <body>
        <h1 class="text-center">Last Mined Block Header</h1>
        <div class="container">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <td>Key</td>
                        <td>Value</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($lastBlockHeader as $key => $value) {
                            echo "<tr>";
                            echo "<td>" . $key . "</td>";
                            echo "<td>" . $value . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="menu1-tab" data-bs-toggle="tab" data-bs-target="#menu1" type="button" role="tab" aria-controls="menu1" aria-selected="true">1</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu2-tab" data-bs-toggle="tab" data-bs-target="#menu2" type="button" role="tab" aria-controls="menu2" aria-selected="false">2</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu3-tab" data-bs-toggle="tab" data-bs-target="#menu3" type="button" role="tab" aria-controls="menu3" aria-selected="false">3</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu4-tab" data-bs-toggle="tab" data-bs-target="#menu4" type="button" role="tab" aria-controls="menu4" aria-selected="false">4</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu5-tab" data-bs-toggle="tab" data-bs-target="#menu5" type="button" role="tab" aria-controls="menu5" aria-selected="false">5</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu6-tab" data-bs-toggle="tab" data-bs-target="#menu6" type="button" role="tab" aria-controls="menu6" aria-selected="false">6</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu7-tab" data-bs-toggle="tab" data-bs-target="#menu7" type="button" role="tab" aria-controls="menu7" aria-selected="false">7</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu8-tab" data-bs-toggle="tab" data-bs-target="#menu8" type="button" role="tab" aria-controls="menu8" aria-selected="false">8</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu9-tab" data-bs-toggle="tab" data-bs-target="#menu9" type="button" role="tab" aria-controls="menu9" aria-selected="false">9</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu10-tab" data-bs-toggle="tab" data-bs-target="#menu10" type="button" role="tab" aria-controls="menu10" aria-selected="false">10</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu11-tab" data-bs-toggle="tab" data-bs-target="#menu11" type="button" role="tab" aria-controls="menu11" aria-selected="false">11</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu12-tab" data-bs-toggle="tab" data-bs-target="#menu12" type="button" role="tab" aria-controls="menu12" aria-selected="false">12</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu13-tab" data-bs-toggle="tab" data-bs-target="#menu13" type="button" role="tab" aria-controls="menu13" aria-selected="false">13</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu14-tab" data-bs-toggle="tab" data-bs-target="#menu14" type="button" role="tab" aria-controls="menu14" aria-selected="false">14</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menu15-tab" data-bs-toggle="tab" data-bs-target="#menu15" type="button" role="tab" aria-controls="menu15" aria-selected="false">15</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="menu16-tab" data-bs-toggle="tab" data-bs-target="#menu16" type="button" role="tab" aria-controls="menu16" aria-selected="false">16</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
                    <h3>1: Le nonce </h3>
                    <p>
                        <?php
                            $chain = $lastBlockHeader["nonce"];
                            echo "Length: " . strlen($lastBlockHeader["nonce"]) . "<br>";
                            echo "<b>" . $lastBlockHeader["nonce"] . "</b>";
                        ?>
                    </p>
                </div>
                <div class="tab-pane fade show" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
                    <h3>2: La difficulté</h3>
                    <p>
                        <?php
                            echo "Length: " . strlen($lastBlockHeader["difficulty"]) . "<br>";
                            $chain = $blockMiner->addParam($chain, $lastBlockHeader["difficulty"]);
                            echo $lastBlockHeader["nonce"] .
                                "<b>" .
                                $lastBlockHeader["difficulty"] .
                                "</b>";
                        ?>
                    </p>
                </div>
                <div class="tab-pane fade show" id="menu3" role="tabpanel" aria-labelledby="menu3-tab">
                    <h3>3: Le timestamp au format hexadecimal</h3>
                    <p>
                        <?php
                            echo "Length: " . strlen((new index)->convertTimestampToHexadecimal($lastBlockHeader["date"])) . "<br>";
                            $chain = $blockMiner->addParam($chain, $blockMiner->convertTimestampToHexadecimal($lastBlockHeader["date"]));
                            echo $lastBlockHeader["nonce"] .
                                $lastBlockHeader["difficulty"] .
                                "<b>" .
                                (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                                "</b>";
                        ?>
                    </p>
                </div>
                <div class="tab-pane fade show" id="menu4" role="tabpanel" aria-labelledby="menu4-tab">
                    <h3>4: Le merkel root</h3>
                    <p>
                        <?php
                            echo "Length: " . strlen($lastBlockHeader["merkel"]) . "<br>";
                            $chain = $blockMiner->addParam($chain, $lastBlockHeader["merkel"]);
                            echo $lastBlockHeader["nonce"] .
                                $lastBlockHeader["difficulty"] .
                                (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                                "<b>" .
                                $lastBlockHeader["merkel"] .
                                "</b>";
                        ?>
                    </p>
                </div>
                <div class="tab-pane fade show" id="menu5" role="tabpanel" aria-labelledby="menu5-tab">
                    <h3>5: Le hash du bloc precedent</h3>
                    <p>
                        <?php
                            echo "Length: " . strlen($lastBlockHeader["previousBlockHash"]) . "<br>";
                            $chain = $blockMiner->addParam($chain, $lastBlockHeader["previousBlockHash"]);
                            echo $lastBlockHeader["nonce"] .
                                $lastBlockHeader["difficulty"] .
                                (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                                $lastBlockHeader["merkel"] .
                                "<b>" .
                                $lastBlockHeader["previousBlockHash"] .
                                "</b>";
                        ?>
                    </p>
                </div>
                <div class="tab-pane fade show" id="menu6" role="tabpanel" aria-labelledby="menu6-tab">
                    <h3>6: La version</h3>
                    <p>
                        <?php
                            $chain = $blockMiner->addParam($chain, $lastBlockHeader["version"]);
                            echo "Length: " . strlen($lastBlockHeader["version"]) . "<br>";
                            echo $lastBlockHeader["nonce"] .
                                $lastBlockHeader["difficulty"] .
                                (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                                $lastBlockHeader["merkel"] .
                                $lastBlockHeader["previousBlockHash"] .
                                "<b>" .
                                $lastBlockHeader["version"] .
                                "</b>";
                        ?>
                    </p>
                </div>
                <div class="tab-pane fade show" id="menu7" role="tabpanel" aria-labelledby="menu7-tab">
                    <h3>7: Split de la chaîne en un tableau de x fois 2 caractères</h3>
                    <?php $chain = $blockMiner->splitChainWithLengthOf2($chain); ?>
                    <table class="table table-dark table-striped">
                        <tr>
                            <?php
                                foreach ($chain as $key => $value) {
                                    if($key% 10 == 0) {
                                        echo "</tr><tr>";
                                    }
                                    echo "<td>".$value."</td>";
                                }
                            ?>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade show" id="menu8" role="tabpanel" aria-labelledby="menu8-tab">
                    <h3>8: Inverse l'ordre des éléments du tableau</h3>
                    <?php $chain = $blockMiner->reverseArray($chain); ?>
                    <table class="table table-dark table-striped">
                        <tr>
                            <?php
                                foreach ($chain as $key => $value) {
                                    if($key% 10 == 0) {
                                        echo "</tr><tr>";
                                    }
                                    echo "<td>".$value."</td>";
                                }
                            ?>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade show" id="menu9" role="tabpanel" aria-labelledby="menu9-tab">
                    <h3>9: Concatenation des éléments du tableau</h3>
                    <?php
                        $chain = $blockMiner->implodeArray($chain);
                        echo "Length: " . strlen($chain) . "<br>";
                        echo "<p>" . $chain . "</p>";
                    ?>
                </div>
                <div class="tab-pane fade show" id="menu10" role="tabpanel" aria-labelledby="menu10-tab">
                    <h3>10: Conversion de la chaîne hexadecimal en binaire</h3>
                    <?php
                        $chain = $blockMiner->convertHexadecimalToBinary($chain);
                        echo "Length: " . strlen($chain) . "<br>";
                        echo $chain;
                    ?>
                </div>
                <div class="tab-pane fade show" id="menu11" role="tabpanel" aria-labelledby="menu11-tab">
                    <h3>11: Hashage du binaire avec l'algorithme SHA256sum</h3>
                    <?php
                        $chain = $blockMiner->hashWithSha256($chain);
                        echo "Length: " . strlen($chain) . "<br>";
                        echo $chain;
                    ?>
                </div>
                <div class="tab-pane fade show" id="menu12" role="tabpanel" aria-labelledby="menu12-tab">
                    <h3>12: Conversion de la chaîne héxadecimale en binaire</h3>
                    <?php
                        $chain = $blockMiner->convertHexadecimalToBinary($chain);
                        echo "Length: " . strlen($chain) . "<br>";
                        echo $chain;
                    ?>
                </div>
                <div class="tab-pane fade show" id="menu13" role="tabpanel" aria-labelledby="menu13-tab">
                    <h3>13: Hashage du binaire avec l'algorithme SHA256sum</h3>
                    <?php
                        $chain = $blockMiner->hashWithSha256($chain);
                        echo "Length: " . strlen($chain) . "<br>";
                        echo $chain;
                    ?>
                </div>
                <div class="tab-pane fade show" id="menu14" role="tabpanel" aria-labelledby="menu14-tab">
                    <h3>14: Split de la chaîne en un tableau de x fois 2 caractères</h3>
                    <?php $chain = $blockMiner->splitChainWithLengthOf2($chain); ?>
                    <table class="table table-dark table-striped">
                        <tr>
                            <?php
                            foreach ($chain as $key => $value) {
                                if($key% 10 == 0) {
                                    echo "</tr><tr>";
                                }
                                echo "<td>".$value."</td>";
                            }
                            ?>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade show" id="menu15" role="tabpanel" aria-labelledby="menu15-tab">
                    <h3>15: Inverse l'ordre des éléments du tableau</h3>
                    <?php $chain = $blockMiner->reverseArray($chain); ?>
                    <table class="table table-dark table-striped">
                        <tr>
                            <?php
                            foreach ($chain as $key => $value) {
                                if($key% 10 == 0) {
                                    echo "</tr><tr>";
                                }
                                echo "<td>".$value."</td>";
                            }
                            ?>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade show active" id="menu16" role="tabpanel" aria-labelledby="menu16-tab">
                    <h3>16: Concatenation des éléments du tableau</h3>
                    <?php
                        $chain = $blockMiner->implodeArray($chain);
                        echo "Length: " . strlen($chain) . "<br>";
                        echo $chain;
                    ?>
                </div>
            </div>
        </div>
        <!--// bootstrap 5 cdn js-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>


