<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\ArgumentException;
use Bitrix\Highloadblock\HighloadBlockTable as HL;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Engine\Contract\Controllerable;

class DklimovRating extends CBitrixComponent implements Controllerable
{
    /**
     * @return mixed|void
     * @throws ArgumentException
     */
    public function executeComponent()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $elementId = $this->arParams['ELEMENT_ID'];
        $iblockId = $this->arParams['ELEMENT_ID'];
        $ratingPropCode = $this->arParams['RATING_PROP_CODE'];
        $this->arResult['UPVOTE_CLASS']=htmlspecialcharsEx($this->arParams['UPVOTE_CLASS'])??"up";
        $this->arResult['DOWNVOTE_CLASS']=$this->arParams['DOWNVOTE_CLASS']??"down";
        $this->arResult['RATING']=$this->getRating($iblockId,$elementId, $ratingPropCode);
        $this->registerAsset();
        $this->IncludeComponentTemplate();
        return $this->arResult;
    }

    public function configureActions()
    {
        return [
            'updateRating' => [
                'prefilters' => [],
            ],
        ];
    }

    public function updateRatingAction($counter,$id,$iblockId,$ratingCode){
        CModule::IncludeModule("iblock");
        $ip = $_SERVER['REMOTE_ADDR'];
        if(!$this->addVoterIp($ip,$id)){
            $message = "Вы не можете голосовать за эту новость";
            return $message;
        }
        $db_props = CIBlockElement::GetProperty($iblockId, $id, array("sort" => "asc"), array("CODE" => $ratingCode));
        if ($ar_props = $db_props->Fetch()) {
            $rating = IntVal($ar_props['VALUE']);
        }
        switch ($counter) {
            case "plus":
                CIBlockElement::SetPropertyValueCode($id, "RATING", ++$rating);
                break;
            case "minus" :
                CIBlockElement::SetPropertyValueCode($id, "RATING", --$rating);
                break;
        }
        $message = "Спасибо за Ваш голос!";
        return $message;
    }
    protected function registerAsset(){
        $asset = Asset::getInstance();
        $asset->addJs($this->getPath().'/rating.js');
        $asset->addString("<script>var upvoteClass='".$this->arResult['UPVOTE_CLASS']."';var downvoteClass='".$this->arResult['DOWNVOTE_CLASS']."';var iblockId='".$this->arParams['IBLOCK_ID']."';var ratingCode='".$this->arParams['RATING_PROP_CODE']."';var id = '".$this->arParams['ELEMENT_ID']."'</script>", true, AssetLocation::AFTER_CSS);
    }
protected function getRating($iblockId,$id,$ratingCode){
    $db_props = CIBlockElement::GetProperty($iblockId, $id, array("sort" => "asc"), array("CODE" => $ratingCode));
    if ($ar_props = $db_props->Fetch()) {
        $rating = IntVal($ar_props['VALUE']);
    }
    return $rating;
}
    protected function addVoterIp($ip,$elementId){
        $entity = $this->connectHl();
        $arProp = array(
            'UF_IP' => $ip,
            'UF_ELEMENT_ID' => $elementId
        );
        $arSelect = array('*');
        $arData = $entity::getList(array(
            "select" => $arSelect,
            "filter" => $arProp
        ));
        while ($arResult = $arData->Fetch()) {
            return false;
        }
        $entity::add($arProp);
        return true;
    }

    protected function connectHl(){
        \CModule::includeModule('highloadblock');
        $hlblock_id = 'voters_ip'; // указываете ид вашего Hig
        $hlBlock = HL::getList(array('filter' => array('TABLE_NAME' => $hlblock_id)))->fetch();
        $entity = HL::compileEntity($hlBlock);
        $entityDataClass = $entity->getDataClass();
        return $entityDataClass;
    }
}