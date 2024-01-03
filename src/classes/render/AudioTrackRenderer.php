<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AudioTrack;

abstract class AudioTrackRenderer implements Renderer
{
    protected AudioTrack $audioTrack;

    public function __construct(AudioTrack $aT)
    {
        $this->audioTrack = $aT;
    }

    public function render(int $selector):string{
        $res = "";
        switch ($selector){
            case Renderer::COMPACT:
                $res = $this->short();
                break;
            case self::LONG:
                $res = $this->long();
                break;
        }
        return $res;
    }

    public abstract function short(): string;

    public abstract function long(): string;
}