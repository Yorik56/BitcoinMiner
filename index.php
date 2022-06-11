<?php

require __DIR__ . '/vendor/autoload.php';


class index
{
    const MAX_TARGET  = 0x00000000FFFF0000000000000000000000000000000000000000000000000000;

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
}

$lastBlockHeader = (new index)->index();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Last Mined Block Header</title>
        <!-- bootstrap 5 cdn       -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
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
            <h2>1: Le nonce </h2>
            <p>
                <?php
                    echo "Lenght: " . strlen($lastBlockHeader["nonce"]) . "<br>";
                    $chain = $lastBlockHeader["nonce"];
                    echo "<b>" . $lastBlockHeader["nonce"] . "</b>";
                ?>
            </p>
            <h2>2: La difficulté</h2>
            <p>
                <?php
                    echo "Lenght: " . strlen($lastBlockHeader["difficulty"]) . "<br>";
                    $chain .= $lastBlockHeader["difficulty"];
                    echo $lastBlockHeader["nonce"] .
                        "<b>" .
                            $lastBlockHeader["difficulty"] .
                        "</b>";
                ?>
            </p>
            <h2>3: Le timestamp au format hexadecimal</h2>
            <p>
                <?php
                    echo "Lenght: " . strlen((new index)->convertTimestampToHexadecimal($lastBlockHeader["date"])) . "<br>";
                    $chain .= (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]);
                    echo $lastBlockHeader["nonce"] .
                        $lastBlockHeader["difficulty"] .
                        "<b>" .
                            (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                        "</b>";
                ?>
            </p>
            <h2>4: Le merkel root</h2>
            <p>
                <?php
                    echo "Lenght: " . strlen($lastBlockHeader["merkel"]) . "<br>";
                    $chain .= $lastBlockHeader["merkel"];
                    echo $lastBlockHeader["nonce"] .
                        $lastBlockHeader["difficulty"] .
                        (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                        "<b>" .
                            $lastBlockHeader["merkel"] .
                        "</b>";
                ?>
            </p>
            <h2>5: Le hash du bloc precedent</h2>
            <p>
                <?php
                    echo "Lenght: " . strlen($lastBlockHeader["previousBlockHash"]) . "<br>";
                    $chain .= $lastBlockHeader["previousBlockHash"];
                    echo $lastBlockHeader["nonce"] .
                        $lastBlockHeader["difficulty"] .
                        (new index)->convertTimestampToHexadecimal($lastBlockHeader["date"]) .
                        $lastBlockHeader["merkel"] .
                        "<b>" .
                            $lastBlockHeader["previousBlockHash"] .
                        "</b>";
                ?>
            </p>
            <h2>6: La version</h2>
            <p>
                <?php
                    echo "Lenght: " . strlen($lastBlockHeader["version"]) . "<br>";
                    $chain .= $lastBlockHeader["version"];
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
            <h2>7: Split de la chaîne en un tableau de x fois 2 caractères</h2>
            <?php
                $chain = str_split($chain, 2);
                echo "<pre>";
                print_r($chain);
                echo "</pre>";
            ?>
            <h2>8: Inverse l'ordre des éléments du tableau</h2>
            <?php
                $chain = array_reverse($chain);
                echo "<pre>";
                print_r($chain);
                echo "</pre>";
            ?>
            <h2>9: Concatenation des éléments du tableau</h2>
            <?php
                echo "Lenght: " . strlen(implode($chain)) . "<br>";
                $chain = implode($chain);
                echo $chain;
            ?>
            <h2>10: Conversion de la chaîne héxadecimale en binaire</h2>
            <?php
                echo "Lenght: " . strlen($chain) . "<br>";
                $chain = hex2bin($chain);
                echo $chain;
            ?>
            <h2>11: Hashage du binaire avec l'algorithme SHA256sum</h2>
            <?php
                echo "Lenght: " . strlen(hash('sha256', $chain)) . "<br>";
                $chain = trim(hash('sha256', $chain));
                echo $chain;
            ?>
            <h2>12: Conversion de la chaîne héxadecimale en binaire</h2>
            <?php
                echo "Lenght: " . strlen($chain) . "<br>";
                $chain = hex2bin($chain);
                echo $chain;
            ?>
            <h2>13: Hashage du binaire avec l'algorithme SHA256sum</h2>
            <?php
                echo "Lenght: " . strlen(hash('sha256', $chain)) . "<br>";
                $chain = trim(hash('sha256', $chain));
                echo $chain;
            ?>
            <h2>14: Split de la chaîne en un tableau de x fois 2 caractères</h2>
            <?php
                $chain = str_split($chain, 2);
                echo "<pre>";
                print_r($chain);
                echo "</pre>";
            ?>
            <h2>15: Inverse l'ordre des éléments du tableau</h2>
            <?php
                $chain = array_reverse($chain);
                echo "<pre>";
                print_r($chain);
                echo "</pre>";
            ?>
            <h2>16: Concatenation des éléments du tableau</h2>
            <?php
                echo "Lenght: " . strlen(implode($chain)) . "<br>";
                $chain = implode($chain);
                echo $chain;
            ?>

        </div>
        <script>
            // bootstrap 5 cdn js
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        </script>
    </body>
</html>


