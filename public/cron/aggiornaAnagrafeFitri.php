<?php

//define('DEBUG', '');

include 'errorHandling.php';

$currentYear = date('Y');
define('SOCIETA_URL', "http://tesseramento.fitri.it/export_tess/societa.php?user=fitritess&pwd=f1tr1t3ss&stagione=$currentYear");
define('ATLETI_URL', 'http://tesseramento.fitri.it/export_tess/atleti.php?user=fitritess&pwd=f1tr1t3ss');
//define('GIUDICI_URL', 'http://tesseramento.fitri.it/export_tess/udg.php?user=fitritess&pwd=f1tr1t3ss');
define('SOCIETA_FNAME', 'societa.csv');
define('ATLETI_FNAME', 'atleti.csv');
define('TESSERATI_TEMP_TABLE_NAME', 'tesserati_fitri_temp');

function fileDownload($fileUrl, $fileHandle) {
    set_time_limit(0); // unlimited max execution time
    $options = array(
        CURLOPT_FILE => $fileHandle,
        CURLOPT_TIMEOUT => 28800, // set this to 8 hours so we dont timeout on big files
        CURLOPT_URL => $fileUrl,
        CURLOPT_HTTPHEADER => array('User-Agent: ArtWare Generic HTTP', 'Cache-Control: no-cache')
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    curl_close($ch);
}

function trimAndNull($field) {
    $r = null;
    if (isset($field)) {
        if ($field !== '') {
            $r = trim($field, '"');
        }
    }
    return $r;
}

function societaLineToCsv($string) {
    // "CODICE SS";"RAGIONE SOCIALE";"RAGIONE SOCIALE BREVE";PROVINCIA;EMAIL
    // 1625;"1A MISTRAL TRIATHLON A.S.DILETTANTISTICA";"1A MISTRAL TRIATHLON";PI;alessandro.perini@piaggio.com
    $string = rtrim($string); // remove trailing newline char
    $fields = explode(';', $string);

    if (is_numeric($fields[0])) {
        $fields[0] = (int) $fields[0]; // codice ss
        $fields[1] = trimAndNull($fields[1]); // ragione sociale
        $fields[2] = trimAndNull($fields[2]); // ragione sociale breve
        $fields[3] = trimAndNull($fields[3]); // provincia
        $fields[4] = trimAndNull($fields[4]); // email
    } else {
        // header
        foreach ($fields as &$field) {
            $field = trim($field, '"');
        }
    }

//    for ($i = 0; $i < count($fields); $i++) {
//        if ($i === 0 && is_numeric($fields[$i])) {
//            $fields[$i] = $fields[$i] + 0;
//        } else {
//            $fields[$i] = trimAndNull($fields[$i]);
//        }
//    }

    return $fields;
}

function atletiLineToCsv($string) {
    // "CODICE SS";TESSERA;COGNOME;NOME;SESSO;"DATA NASCITA";CITTADINANZA;CATEGORIA;QUALIFICA;LIVELLO;STATO;"DATA EMISSIONE";"TIPO TESSERA";DISABILITA
    // 1769;52345;Abagnale;Michele;M;10/05/1969;Italia;Agonista;Master;"Master 2";;17/04/2015;Atleta;
    $string = rtrim($string); // remove trailing newline char
    $fields = explode(';', $string);

    if (is_numeric($fields[0])) {
        $fields[0] = (int) $fields[0]; // codice ss
        $fields[1] = (int) $fields[1]; // tessera
        $fields[2] = trimAndNull($fields[2]); // cognome
        $fields[3] = trimAndNull($fields[3]); // nome
        $fields[4] = trimAndNull($fields[4]); // sesso
        $fields[5] = DateTime::createFromFormat('d/m/Y', $fields[5]); // data nascita
        $fields[6] = trimAndNull($fields[6]); // cittadinanza
        $fields[7] = trimAndNull($fields[7]); // categoria
        $fields[8] = trimAndNull($fields[8]); // qualifica
        $fields[9] = trimAndNull($fields[9]); // livello
        $fields[10] = trimAndNull($fields[10]); // stato
        $fields[11] = DateTime::createFromFormat('d/m/Y', $fields[11]); // data emissione
        $fields[12] = trimAndNull($fields[12]); // stato
        $fields[13] = trimAndNull($fields[13]); // stato
    } else {
        // header
        foreach ($fields as &$field) {
            $field = trim($field, '"');
        }
    }

//    for ($i = 0; $i < count($fields); $i++) {
//        if (($i === 0 || $i === 1) && is_numeric($fields[$i])) {
//            $fields[$i] = $fields[$i] + 0;
//        }
//        if (($i === 6 || $i === 12)) {
//            $temp = DateTime::createFromFormat('Y/m/d', $fields[$i]);
//            if ($temp && ($temp->format('Y/m/d') === $fields[$i])) {
//                $fields[$i] = $temp;
//            } else {
//                $fields[$i] = trim($fields[$i], '"');
//            }
//        } else {
//            $fields[$i] = trim($fields[$i], '"');
//        }
//    }

    return $fields;
}

function csvToAssociative($csvFileName, $callbackFunctionName) {
    $rows = array_map($callbackFunctionName, file($csvFileName));
    $header = array_shift($rows);
    $csv = array();
    foreach ($rows as $row) {
        $csv[] = array_combine($header, $row);
    }
    return $csv;
}

function databaseConnect($mysqlConf) {
//    $mysqli = mysqli_init();
//    $mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
//    $mysqli->real_connect("example.com", "user", "password", "database");

    $conn = new mysqli(
            $mysqlConf['server'], $mysqlConf['username'], $mysqlConf['password'], $mysqlConf['database']);
    if ($conn->connect_errno) {
        throw new Exception($mysqli->connect_error);
    }
    return $conn;
}

/**
 * New societa are added to the table and no one is removed, even if it not present in the
 * downloaded CSV. This is because data needs to be maintened for future reference.
 */
function addSocieta($conn, $societa) {
    $n = 0;
    $codice = null;

    if (!($selectStmt = $conn->prepare('SELECT codice FROM societa_fitri WHERE codice = ?'))) {
        throw new Exception($conn->errno . ' ' . $conn->error);
    }
    if (!($selectStmt->bind_param('i', $codice))) {
        throw new Exception($selectStmt->errno . ' ' . $selectStmt->error);
    }

    $societaToAdd = array();

    foreach ($societa as $s) {
        $codice = $s['CODICE SS'];
        $selectStmt->execute();
        if ($selectStmt->errno) {
            throw new Exception($selectStmt->errno . ' ' . $selectStmt->error);
        }
        $selectStmt->bind_result($r);
        $selectStmt->fetch();
        if (!isset($r)) {
            $societaToAdd[] = $s;
        }
    }
    $selectStmt->close();

    if (count($societaToAdd) > 0) {
        $n = count($societaToAdd);
        $nome = null;
        $provincia = null;
        $email = null;

        if (!($insertStmt = $conn->prepare('INSERT INTO societa_fitri '
                . ' (codice, provincia, nome, email)'
                . " VALUES (?, ?, ?, ?)"))) {
            throw new Exception($conn->errno . ' ' . $conn->error);
        }
        if (!($insertStmt->bind_param('isss', $codice, $provincia, $nome, $email))) {
            throw new Exception($insertStmt->errno . ' ' . $insertStmt->error);
        }

        foreach ($societaToAdd as $s) {
            $codice = $s['CODICE SS'];
            $nome = $s['RAGIONE SOCIALE'];
            $provincia = $s['PROVINCIA'];
            $email = $s['EMAIL'];
            $insertStmt->execute();
            if ($insertStmt->errno) {
                throw new Exception($insertStmt->errno . ' ' . $insertStmt->error);
            }
        }

        $insertStmt->close();
    }
    return $n;
}

function createTempTable($conn) {
//    "CODICE SS";TESSERA;COGNOME;NOME;SESSO;"DATA NASCITA";CITTADINANZA;CATEGORIA;QUALIFICA;LIVELLO;STATO;"DATA EMISSIONE";"TIPO TESSERA";DISABILITA

    $query = "CREATE TEMPORARY TABLE `" . TESSERATI_TEMP_TABLE_NAME . "` (
  `CODICE_SS` int(11) NOT NULL,
  `TESSERA` int(11) NOT NULL,
  `COGNOME` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NOME` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SESSO` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DATA_NASCITA` date NOT NULL,
  `CITTADINANZA` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CATEGORIA` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `QUALIFICA` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LIVELLO` varchar(50) COLLATE utf8mb4_unicode_ci NULL,
  `STATO` varchar(50) COLLATE utf8mb4_unicode_ci NULL,
  `DATA_EMISSIONE` date NOT NULL,
  `TIPO_TESSERA` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DISABILITA` varchar(50) COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`TESSERA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
    $conn->query($query);
    if ($conn->errno) {
        throw new Exception($conn->errno . ' ' . $conn->error);
    }
}

function insertIntoTempTable($conn, $atleti) {
    $n = 0;

    $CODICE_SS = null;
    $TESSERA = null;
    $COGNOME = null;
    $NOME = null;
    $SESSO = null;
    $DATA_NASCITA = null;
    $CITTADINANZA = null;
    $CATEGORIA = null;
    $QUALIFICA = null;
    $LIVELLO = null;
    $STATO = null;
    $DATA_EMISSIONE = null;
    $TIPO_TESSERA = null;
    $DISABILITA = null;

    if (!($insertStmt = $conn->prepare('INSERT INTO `' . TESSERATI_TEMP_TABLE_NAME . '` '
            . '(`CODICE_SS`,'
            . '`TESSERA`,'
            . '`COGNOME`,'
            . '`NOME`,'
            . '`SESSO`,'
            . '`DATA_NASCITA`,'
            . '`CITTADINANZA`,'
            . '`CATEGORIA`,'
            . '`QUALIFICA`,'
            . '`LIVELLO`,'
            . '`STATO`,'
            . '`DATA_EMISSIONE`,'
            . '`TIPO_TESSERA`,'
            . '`DISABILITA` )'
            . ' VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'))) {
        throw new Exception($conn->error);
    }
    if (!($insertStmt->bind_param('iissssssssssss', $CODICE_SS, $TESSERA, $COGNOME, $NOME, $SESSO, $DATA_NASCITA, $CITTADINANZA, $CATEGORIA, $QUALIFICA, $LIVELLO, $STATO, $DATA_EMISSIONE, $TIPO_TESSERA, $DISABILITA))) {
        throw new Exception($selectStmt->errno . ' ' . $selectStmt->error);
    }

    foreach ($atleti as $a) {
        $CODICE_SS = $a['CODICE SS'];
        $TESSERA = $a['TESSERA'];
        $COGNOME = $a['COGNOME'];
        $NOME = $a['NOME'];
        $SESSO = $a['SESSO'];
        $DATA_NASCITA = $a['DATA NASCITA']->format('Y/m/d');
        $CITTADINANZA = $a['CITTADINANZA'];
        $CATEGORIA = $a['CATEGORIA'];
        $QUALIFICA = $a['QUALIFICA'];
        $LIVELLO = $a['LIVELLO'];
        $STATO = $a['STATO'];
        $DATA_EMISSIONE = $a['DATA EMISSIONE']->format('Y/m/d');
        $TIPO_TESSERA = $a['TIPO TESSERA'];
        $DISABILITA = $a['DISABILITA'];

        $insertStmt->execute();
        if ($insertStmt->errno) {
            switch ($insertStmt->errno) {
                case 1062:
                    error_log('Duplicate tessera: '. json_encode($a));
                    break;
                default:
                    throw new Exception($insertStmt->errno . ' ' . $insertStmt->error);
            }
            /*
             * TESSERA is not unique, since there can be cases like the following
              1305;57078;Reggianini;Cristian;M;03/02/1978;Italia;Agonista;Senior;"Senior 4";;30/01/2015;Atleta;
              1789;57078;Reggianini;Cristian;M;03/02/1978;Italia;Agonista;Senior;"Senior 4";Age-group;25/04/2015;Atleta;
             * 
             * The error number related to duplicated key is 1062
             */
        } else {
            $n++;
        }
    }

    $insertStmt->close();
    return $n;
}

function updateAtleti($conn, $atleti) {
    $n = 0;

    echo '<p>Creating temporary table for storing downloaded atleti data...';
    createTempTable($conn);
    echo ' done</p>';
    echo '<p>Inserting downloaded data into temporary table...';
    $n = insertIntoTempTable($conn, $atleti);
    echo " done ($n)</p>";

    $query = 'DELETE FROM `tesserati_fitri` WHERE `TESSERA` NOT IN (SELECT `TESSERA` FROM `' . TESSERATI_TEMP_TABLE_NAME . '`);';
    $conn->query($query);
    $n = $conn->affected_rows;
    echo "<p>Deleted $n atleti from tesserati_fitri</p>";

    $fieldList = '`CODICE_SS`,'
            . '`TESSERA`,'
            . '`COGNOME`,'
            . '`NOME`,'
            . '`SESSO`,'
            . '`DATA_NASCITA`,'
            . '`CITTADINANZA`,'
            . '`CATEGORIA`,'
            . '`QUALIFICA`,'
            . '`LIVELLO`,'
            . '`STATO`,'
            . '`DATA_EMISSIONE`,'
            . '`TIPO_TESSERA`,'
            . '`DISABILITA`';
    $query = 'INSERT INTO `tesserati_fitri` '
            . '('
            . $fieldList
            . ')'
            . ' SELECT '
            . $fieldList
            . ' FROM `' . TESSERATI_TEMP_TABLE_NAME . '` WHERE `TESSERA` NOT IN (SELECT `TESSERA` FROM `tesserati_fitri`);';
    $conn->query($query);
    if ($conn->errno) {
        throw new Exception($conn->errno . ' ' . $conn->error);
    }

    $n = $conn->affected_rows;
    echo "<p>Inserted $n new atleti into tesserati_fitri</p>";

    return $n;
}

$conf = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR .'rest-api' . DIRECTORY_SEPARATOR .'config.ini', true);
$mysqlConf = $conf['mysql'];




$societaFName = 'societa.csv';
$societaFH = fopen($societaFName, 'w');

echo '<p>Downloading file of societa...';
fileDownload(SOCIETA_URL, $societaFH);
echo ' done</p>';
fclose($societaFH);


$atletiFName = 'atleti.csv';
$atletiFH = fopen($atletiFName, 'w');
echo '<p>Downloading file of atleti...';
fileDownload(ATLETI_URL, $atletiFH);
echo ' done</p>';
fclose($atletiFH);

echo '<p>Converting societa CSV to associative...';
$societa = csvToAssociative(SOCIETA_FNAME, 'societaLineToCsv');
$n = count($societa);
echo " done ($n)</p>";
echo '<p>Converting atleti CSV to associative...';
$atleti = csvToAssociative(ATLETI_FNAME, 'atletiLineToCsv');
$n = count($atleti);
echo " done ($n)</p>";

////--- comodo
//for ($i = 0; $i < count($societa); $i++) {
//    $provincia = $societa[$i]['PROVINCIA'];
//    $email = $societa[$i]['EMAIL'];
//    $codice = $societa[$i]['CODICE SS'];
//    echo "UPDATE societa_fitri SET provincia = '$provincia', email = '$email' WHERE codice = $codice;</br>";
//}

$conn = databaseConnect($mysqlConf);
$n = addSocieta($conn, $societa);
echo "<p>Added $n societ&agrave;</p>";
$n = updateAtleti($conn, $atleti);
$conn->close();
//unlink($societaFName);
//unlink($atletiFName);

