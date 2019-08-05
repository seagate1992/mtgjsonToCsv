<?php
    class Card{
        private $name;
        private $number;
        private $types;
        private $superType;
        private $subType;
        private $power;
        private $toughness;
        private $text;
        private $colorIdentity;
        private $convertedManaCost;
        
        public function __construct($Card){
            $this->name = $Card["name"];
            $this->number = $Card["number"];
            $this->types = $Card["types"];
            
            if(array_key_exists("supertypes", $Card)){
                $this->superType=$Card["supertypes"];
            }
            if(array_key_exists("subtypes", $Card)){
                $this->subType=$Card["subtypes"];
            }
            if(array_key_exists("power", $Card)){
                $this->power = $Card["power"];
            }
            if(array_key_exists("toughness", $Card)){
                $this->toughness = $Card["toughness"];
            }
            if(array_key_exists("text", $Card)){
               $this->text = $Card["text"];
               $this->text = trim(preg_replace('/\s+/', ' ', $this->text));
            }
            if(array_key_exists("colors", $Card)){
                $this->colorIdentity = $Card["colors"];
            }
            $this->convertedManaCost = $Card["convertedManaCost"];
        }
        
        public function GetCardCsv(){
            $tmp = "";
            
            $tmp .= $this->number."\t".$this->name."\t".implode($this->types,"/")."\t";
            
            if($this->superType == NULL){
                $tmp .= "\t";
            }else{
                $tmp.=implode($this->superType,"/")."\t";
            }
            
            if($this->subType == NULL){
                $tmp .= "\t";
            }else{
                $tmp.=implode($this->subType, "/")."\t";
            }
            
            if($this->power == NULL){
                $tmp .= "\t";
            }else{
                $tmp.=$this->power."\t";
            }
            
            if($this->toughness == NULL){
                $tmp .= "\t";
            }else{
                $tmp.=$this->toughness."\t";
            }
            
            if($this->text == NULL){
                $tmp .= "\t";
            }else{
                $tmp.=$this->text."\t";
            }
            
            if($this->colorIdentity == NULL){
                $tmp .= "\t";
            }else{
                $tmp.=implode($this->colorIdentity, "/")."\t";
            }
            
            $tmp .= $this->convertedManaCost;
            
            return $tmp;
        }
    }
    
    class Set{
        private $name;
        private $cards;
        
        public function __construct($jsonStream){
            $this->name = $this->GetSetName($jsonStream);
            $this->cards = $this->getCards($jsonStream);
        }
        
        function GetSetName($jsonStream){
            return $jsonStream["name"];
        }
        
        function GetCards($jsonStream){
            $cardSet = array();
            $i=0;
            foreach($jsonStream["cards"] as $NewCard){
                $cardSet[$i] = new Card($NewCard);
                $i++;
            }
            //var_dump($cardSet);
            return $cardSet;
        }
        
        public function SetToCsv($filename){
            $fp = fopen($filename, "a");
            $tmp="set\tnumber\tcardName\ttypes\tsuperType\tsubtype\tpower\ttoughness\ttext\tcolorIdentity\tconvertedManaCost\n";
            fwrite($fp, $tmp);
            foreach($this->cards as $card){
                $tmp="";
                $tmp = $this->name."\t".$card->GetCardCsv()."\n";
                fwrite($fp, $tmp);
            }
            fclose($fp);
        }
    }


?>
