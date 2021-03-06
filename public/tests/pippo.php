<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$test = array('pippo' => true, 'data' => new DateTime());
var_dump($test);
$json = json_encode($test);
echo $json;
var_dump(json_decode($json, true));

function _sqlFormat($field) {
    $r = 'NULL';
    if (isset($field)) {
        if (is_string($field)) {
            $r = "'$field'";
        } else if (is_bool($field)) {
            $r = ($field) ? '1' : '0';
        } else {
            $r = (string) $field;
        }
    }
    return $r;
}

function where_helper($clauses, $andor) {
    $r = null;
    $first = true;
    foreach ($clauses as $field => $value) {
        if ($first) {
            $first = false;
            $r = '(';
        } else {
            $r .= " $andor ";
        }

        if ($field === 'or' || $field === 'OR') {
            $r .= where_helper($value, 'OR');
        } else {
            $op = '=';
            if (is_array($value)) {
                // necessariamente un operatore diverso dall'uguale
                if (count(array_keys($value)) === 1) {
                    $opDefinition = array_keys($value)[0];
                    if ($opDefinition) {
                        switch ($opDefinition) {
                            case 'lt':
                                $op = '<';
                                break;
                            case 'le':
                                $op = '<=';
                                break;
                            case 'gt':
                                $op = '>';
                                break;
                            case 'ge':
                                $op = '>=';
                                break;
                            case 'eq':
                                $op = '=';
                                break;
                            case 'ne':
                                $op = '!=';
                                break;
                            case 'like':
                                $op = 'LIKE';
                                break;
                            default:
                                throw new Exception('Unexptected operator definition (' . $opDefinition . ')');
                        }
                        $value = $value[$opDefinition];
                    } else {
                        throw new Exception('Malformed clause ' . var_export($value, true)); // stampa array
                    }
                } else {
                    throw new Exception('Malformed clause ' . var_export($value, true)); // stampa array
                }
            }
//            $field = $this->conn->escape_string($field);
//            $value = $this->conn->escape_string($value);
            $r .= "`$field` $op " . _sqlFormat($value);
        }
    }
    $r .= ')';
    return $r;
}

function where($clauses) {
    return where_helper($clauses, 'AND');
}

echo is_array(['francesco']);
echo '<p>' . where(['id' => 46]);
echo '<p>' . where(['id' => 46, 'nome' => 'francesco']);
try {
    echo '<p>' . where(['francesco']);
} catch (Exception $e) {
    echo '<p>' . $e->getMessage();
}
echo '<p>' . where(['or' => ['id' => 46, 'nome' => 'pippo']]);
echo '<p>' . where(['hasCognome' => true, 'or' => ['id' => 46, 'nome' => 'pippo']]);
echo '<p>' . json_encode(['hasCognome' => true, 'or' => ['id' => 46, 'nome' => 'pippo']]);
echo '<p>' . where(['hasCognome' => true, 'or' => ['id' => ['gt' => 46], 'nome' => 'pippo']]);
echo '<p>' . where(['hasCognome' => true, 'or' => ['id' => ['gt' => 46], 'nome' => ['like' => 'pippo']]]);

try {
    echo '<p>' . where(['hasCognome' => true, 'or' => ['id' => ['gt' => 46], 'nome' => ['pippo']]]);
} catch (Exception $e) {
    echo '<p>' . $e->getMessage();
}

var_dump($_GET);
var_dump($_SERVER['QUERY_STRING']);
var_dump($_POST);
var_dump(isset($_POST));
var_dump(json_decode('"pippo"'));

echo mail('fratrotta@gmail.com', 'Ecco un bel messaggio di prova', 'Messaggio di prova con un testo molto interessante. Questo lo scriviamo un po lungo in modo che, possibilmente non venga filtrato');
