<?php
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList
{
    protected string $nom;
    protected int $dureeTotale;
    protected int $nbPistes;
    protected array $tableauPistes;

    public function __construct(string $n, array $p = []){
        $this->dureeTotale = 0;
        $this->nbPistes = 0;
        foreach($p as $value){
            $this->dureeTotale += $value->__get("duree");
            $this->nbPistes += 1;
        }
        $this->nom = $n;
        $this->tableauPistes = $p;
    }

    public function __get(string $nom){
        if(property_exists($this, $nom)){
            return $this->$nom;
        }else{
            throw new InvalidPropertyNameException(get_called_class() . " invalid property : \"$nom\"");
        }
    }
}