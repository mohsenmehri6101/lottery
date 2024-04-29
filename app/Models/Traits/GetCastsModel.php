<?php

namespace App\Models\Traits;

trait GetCastsModel
{
    public function getCasts(): array
    {
        return property_exists($this,'casts') ? $this->casts : [];
    }

    public function setCasts($casts=null,$key=null,$value=null)
    {
        if(is_array($casts) && !empty($casts)){
            $this->casts = $casts;
        }
        elseif (filled($key) && filled($value)){
            $this->casts[$key]= $value;
        }
    }
}
