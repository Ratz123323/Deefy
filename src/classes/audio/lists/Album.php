<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class Album extends AudioList
{
    private string $artiste;
    private string $dateSortie;

    public function __construct(string $n, array $pistes){
        parent::__construct($n, $pistes);
        $this->artiste = "inconnu";
        $this->dateSortie = "inconnue";
    }

    public function __set(string $val):void{
        if($val == "artiste"){
            $this->artiste = $val;
        }else if($val == "dateSortie"){
            $this->dateSortie = $val;
        }else{
            throw new InvalidPropertyNameException(get_called_class() . " invalid property : \"$val\"");
        }
    }
}