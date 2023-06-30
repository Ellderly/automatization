<?php

    $campaignKey = '7vevht3gy0f1t1mc2obm';

    $ApiKey='1000001e366a5e14f4e09bdd121d3575593ad4c';
    $CampaignLink='https://successes0o.website/click.php?key=' . $campaignKey;
    $getClick=new getClick($CampaignLink, $ApiKey);
    $clickData = $getClick->DataClick;

    class getClick{
        /*
        * Binom ClickAPI
        * @version 1.16
        * @date 25.08.2021
        **/
        function __construct($CampaignLink, $ApiKey){
            if(strpos($CampaignLink, '?')!==false){
                $this->ClickURL=$CampaignLink.'&lp_type=click_info&api_key='.$ApiKey;
            }else{
                $this->ClickURL=$CampaignLink.'?lp_type=click_info&api_key='.$ApiKey;
            }
            if(isset($_GET)){
                foreach($_GET AS $key=>$val){
                    $this->ClickURL=$this->ClickURL.'&'.$key.'='.urlencode($val);
                }
            }
            $this->DataClick=$this->getClickData($this->ClickURL);
        }
        function setLPClick(){
            $URL=$this->getLPClickURL();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $result = curl_exec( $ch );
            curl_close( $ch );
            return true;
        }
        function getLPClickURL($emulation=1){
            if(isset($this->ClickURL) && isset($this->DataClick['uclick'])){
                $tempArr=explode('?',$this->ClickURL);
                if($emulation==1){
                    $LPClickURL=$tempArr[0].'?lp=1&emulation_mode=1&uclick='.$this->DataClick['uclick'];
                }else{
                    $LPClickURL=$tempArr[0].'?lp=1&uclick='.$this->DataClick['uclick'];
                }
                return $LPClickURL;
            }
            return false;
        }
        function getLanding(){
            if(isset($this->DataClick['landing']['type'])){
                if($this->DataClick['landing']['id']==0 || $this->DataClick['landing']['name']=='DIRECT'){
                    echo 'Direct';
                }else{
                    if($this->DataClick['landing']['type']==2 && isset($this->DataClick['landing']['html'])){
                        echo $this->DataClick['landing']['html'];
                    }else{
                        echo $this->loadLanding();
                    }
                }
            }
        }
        function includeLanding(){
            ob_start();
            include($this->getLandingUrl());
            return $this->replaceLandingLink(ob_get_clean());
        }
        function loadLanding(){
            $postdata = http_build_query($this->DataClick);
            $opts = array('http' =>array(
                'method'  => 'POST','header'  => 'Content-type: application/x-www-form-urlencoded','content' => $postdata
            ));
            $context  = stream_context_create($opts);
            return $this->replaceLandingLink(file_get_contents(
                $this->getLandingUrl(), false, $context
            ));
        }
        function replaceLandingLink($html){
            if(isset($this->DataClick['uclick'])){
                $html=str_replace('?lp=1','?lp=1&uclick='.$this->DataClick['uclick'],$html);
            }
            return $html;
        }
        function getOfferUrl(){
            $OfferUrl='Unknown';
            if(isset($this->DataClick['offer']['url'])){
                $OfferUrl=$this->DataClick['offer']['url'];
            }
            return $OfferUrl;
        }
        function getLandingUrl(){
            $LandingUrl='Unknown';
            if(isset($this->DataClick['landing']['url'])){
                if($this->DataClick['landing']['id']=='0'){
                    $LandingUrl='Direct';
                }else{
                    $LandingUrl=$this->DataClick['landing']['url'];
                }
            }
            return $LandingUrl;
        }
        function getClickData($ClickURL){
            $ClickOptions=$this->getClickOptions();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ClickURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            if(!empty($ClickOptions)){
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $ClickOptions);
            }
            $result = curl_exec( $ch );
            curl_close( $ch );
            if(strpos($result,'<body><!-- adp-fngr-prnt --><script>')) {
                echo $result; return false;
            }
            if(!$result=json_decode($result,true)){
                $result['status']='error';
                $result['error']='Incorrect Campaign link';
            }
            return $result;
        }
        function getClickOptions(){
            $posts=array();
            if(isset($_POST) && !empty($_POST)){
                foreach($_POST AS $key=>$val){
                    $posts[]=$key.'='.$val;
                }
            }
            $Headers=array();
            foreach($_SERVER AS $key=>$val){
                if(strtolower(substr($key, 0, 5)) === 'http_' || strtolower($key)=='remote_addr') {
                    $Headers[$key]=$val;
                }
            }
            if(!isset($Headers['HTTP_CONTENT_TYPE'])){
                $Headers['HTTP_CONTENT_TYPE']='text/html; charset=utf-8';
            }
            if(!isset($Headers['HTTP_X_FORWARDED_FOR']) && isset($Headers['REMOTE_ADDR'])){
                $Headers['HTTP_X_FORWARDED_FOR']=$Headers['REMOTE_ADDR'];
            }
            $posts[]='ClickDataHeaders='.urlencode(json_encode($Headers));
            return implode('&',$posts);
        }
    }
?>